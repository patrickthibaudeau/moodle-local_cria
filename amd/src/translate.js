import $ from 'jquery';
import ajax from 'core/ajax';
import config from 'core/config';

export const init = () => {
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
        var voice = $('input[name=voice]:checked').val();
        var paraphrase = $('#paraphrase').val();
            var prompt = '[INSTRUCTIONS]Forget any previous translations and ' ;

        $('#cria-translation-container').html('<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">...</span>');

        if (language == 'fr') {
            prompt += 'translate the context into French';
        } else {
            prompt += 'translate the context into English';
        }

        if (paraphrase == 'yes') {
            prompt += ' and paraphrase the translation without changing the core message while';
        }

        switch (voice) {
            case "0":
                prompt += ' using a neutral tone.';
                break;
            case "1":
                prompt += ' using a formal tone.';
                break;
            case "2":
                prompt += ' using a an informal tone.';
                break;
            case "3":
                prompt += ' using an academic tone.';
                break;
            case "4":
                prompt += ' using a literary tone.';
                break;
        }

        prompt += ' Only provide one translation.[/INSTRUCTIONS]';


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

            $('#starting-process').hide();
            $('#almost-done').show();
            // JQuery Ajax call to process.php
            setTimeout(function () {
                $('#almost-done').hide();
                $('#process-notes').show();
                $('#cria-cost').html('$' + data.cost.toPrecision(6));
                $('#cria-translation-container').html(data.message);
                $('#cria-cost').show();
            }, 2000);

            // $('#cria-prompt-tokens').html(data.prompt_tokens);
            // $('#cria-completion-tokens').html(data.completion_tokens);
            // $('#cria-total-tokens').html(data.total_tokens);

        }).fail(function () {
            alert('An error has occurred.');
        });
    });
}