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
        var content = 'rubric:' + $('#rubric').val() + '\n' + 'assignment:' + $('#assignment').val();
        var voice = $('input[name=voice]:checked').val();
        var paraphrase = $('#paraphrase').val();

        // Make ajax call to get bot user prompt
        var bot_prompt_response = ajax.call([{
            methodname: 'cria_get_bot_prompt',
            args: {
                bot_id: bot_id,
            }
        }]);
        // get bot user prompt results
        bot_prompt_response[0].done(function (result) {
            let prompt = '[INSTRUCTIONS]';
            prompt += result;
            prompt += '[/INSTRUCTIONS]';

            // Now that we have the prompt, make the call to GPT for results
            $('#process-notes').hide();
            $('#starting-process').show();
            //Delete the record
            var gpt_response = ajax.call([{
                methodname: 'cria_get_gpt_response',
                args: {
                    bot_id: bot_id,
                    chat_id: 'none',
                    prompt: prompt,
                    content: content
                }
            }]);

            gpt_response[0].done(function (result) {
                $('#starting-process').hide();
                $('#almost-done').show();
                // JQuery Ajax call to process.php
                setTimeout(function () {
                    $('#almost-done').hide();
                    $('#process-notes').show();
                    $('#cria-cost').html('$' + result.cost.toPrecision(6));
                    $('#cria-translation-container').html(result.message);
                    $('#cria-cost').show();
                }, 2000);

            }).fail(function () {
                alert('An error has occurred.');
            });
        }).fail(function () {
            alert('An error has occurred. Prompt was not returned properly');
        });

        console.log(prompt);

    });
}