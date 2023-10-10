import $ from 'jquery';
import ajax from 'core/ajax';
import config from 'core/config';

export const init = () => {
    // approximate_cost();
    process_notes();
};

/**
 * Delete a content
 */
function process_notes() {

    $("#process-notes").off();
    $("#process-notes").on('click', function () {
        $('#cria-cost').hide();
        var bot_id = $(this).data('bot_id');
        var language = $('#language').val();
        var content = '[CONTEXT]' + $('#notes').val() + '[/CONTEXT]'
        var prompt = `[INSTRUCTIONS]Create meeting notes from the context provided and separate the notes by topic. Each topic should be in a 
        numbered list. Once done, create all action items from the context. Format the action items as a list having 
        the following headings: Assigned to, Description, Date due[/INSTRUCTIONS]`;
        if (language == 'fr') {
            prompt = `[INSTRUCTIONS]Créez des notes de réunion à partir du context et séparez les notes par sujet. Chaque sujet doit être dans une 
            liste numérotée. Une fois terminé, créez toutes les tâches à partir de la transcription. Formatez les 
            tâches comme une list ayant les entêtes suivantes: Assigné à, Description, Date d'échéance[/INSTRUCTIONS]`;
        }
        // Get all values from various fields
        var project_name = $('#project_name').val();
        var date = $('#date').val();
        var time = $('#time').val();
        var location = $('#location').val();

        $('#process-notes').hide();
        $('#starting-process').show();
        //Delete the record
        var gpt_response = ajax.call([{
            methodname: 'cria_get_gpt_response',
            args: {
                bot_id: bot_id,
                chat_id: 0,
                prompt: prompt,
                content: content
            }
        }]);

        gpt_response[0].done(function (result) {
            let data = JSON.parse(result);
            let form_data = {
                'project_name': project_name,
                'date': date,
                'time': time,
                'location': location,
                'minutes': data.message
            };
            $('#starting-process').hide();
            $('#almost-done').show();
            // JQuery Ajax call to process.php
            setTimeout(function () {
                $('#almost-done').hide();
                $('#process-complete').show();
                $.post(config.wwwroot + "/local/cria/plugins/minutes_master/process.php", form_data, function (response) {
                    let path_data = JSON.parse(response);
                    window.location.href = config.wwwroot + "/local/cria/plugins/minutes_master/download.php?path="
                        + path_data.path + "&file=" + path_data.file_name;
                    setTimeout(function () {
                        $('#cria-cost').show();
                        $('#cria-cost').html('$' + data.cost.toPrecision(6));
                        $('#process-complete').hide();
                        $('#process-notes').show();
                    }, 1500);
                });
            }, 2000);


            $('#cria-message').html(data.message);
            // $('#cria-prompt-tokens').html(data.prompt_tokens);
            // $('#cria-completion-tokens').html(data.completion_tokens);
            // $('#cria-total-tokens').html(data.total_tokens);

        }).fail(function () {
            alert('An error has occurred.');
        });
    });
}

function approximate_cost() {
    $('#notes').on('change', function () {
        var bot_id = $('#cria-bot-id').val();
        var language = $('#language').val();
        var content = '[CONTEXT]' + $('#notes').val() + '[/CONTEXT]'
        var prompt = `[INSTRUCTIONS]Create meeting notes from the context provided and separate the notes by topic. Each topic should be in a 
        numbered list. Once done, create all action items from the context. Format the action items as a list having 
        the following headings: Assigned to, Description, Date due[/INSTRUCTIONS]`;
        if (language == 'fr') {
            prompt = `[INSTRUCTIONS]Créez des notes de réunion à partir du context et séparez les notes par sujet. Chaque sujet doit être dans une 
            liste numérotée. Une fois terminé, créez toutes les tâches à partir de la transcription. Formatez les 
            tâches comme une list ayant les entêtes suivantes: Assigné à, Description, Date d'échéance[/INSTRUCTIONS]`;
        }

        var gpt_response = ajax.call([{
            methodname: 'cria_get_approximate_cost',
            args: {
                bot_id: bot_id,
                prompt: prompt,
                content: content
            }
        }]);

        gpt_response[0].done(function (result) {
            let data = JSON.parse(result);
            $('#cria-aprox-cost').html('Approximate cost: $' + data.cost.toPrecision(6));
            $('#cria-aprox-cost').show();
        });

    });
}