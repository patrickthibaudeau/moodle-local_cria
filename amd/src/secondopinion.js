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
        var prompt = '[INSTRUCTIONS]';
        prompt += 'Evaluate the following student assessment based on the provided rubric. Separate each skills into a list and always provide a sentence ' +
            'on why that score was provided. Provide the final ' +
            'numerical score as a percentage and a corresponding letter grade. The total possible score is the highest ' +
            'points that can be obtained in each criteria. To provide a final score, do the math based on the following formula: (((total score obtained on the assignment) / total possible score)*100)\n' +
            'Do not show the math formula in the response. Only print the Final score result based on the previous formula. At the bottom, provide 3-5 answers to the student on improving their writing moving forward.'
        prompt += '[/INSTRUCTIONS]';


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