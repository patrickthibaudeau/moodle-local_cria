import $ from 'jquery';
import ajax from 'core/ajax';

export const init = () => {
    get_response();
};

/**
 * Delete a content
 */
function get_response() {

    $("#submit-question").off();
    $("#submit-question").on('click', function () {
        var bot_id = $(this).data('bot_id');
        var prompt = '[INSTRUCTIONS]' + $('#user-prompt').val() + '[/INSTRUCTIONS]'
        var content  = '[CONTEXT]' + $('#cria-test-input').val() + '[/CONTEXT]'
        var chat_id = $('#cria-chat-id').val();
        $("#submit-question").hide();
        $("#starting-process").show();
        //Delete the record
        var gpt_response = ajax.call([{
            methodname: 'cria_get_gpt_response',
            args: {
                bot_id: bot_id,
                chat_id: chat_id,
                prompt: prompt,
                content: content
            }
        }]);

        gpt_response[0].done(function (result) {
            // console.log(result);
            let data = JSON.parse(result);
            $("#submit-question").show();
            $("#starting-process").hide();
            $('#cria-conversation').append().html(`<p> ${data.message}</p>`);
            $('#cria-prompt-tokens').html(data.prompt_tokens);
            $('#cria-completion-tokens').html(data.completion_tokens);
            $('#cria-total-tokens').html(data.total_tokens);
            $('#cria-cost').html('$' + data.cost.toPrecision(6));
        }).fail(function () {
            alert('An error has occurred.');
        });
    });
}