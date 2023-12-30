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
        // rest statistics
        $('#cria-conversation').html('');
        $('#cria-prompt-tokens').html(0);
        $('#cria-completion-tokens').html(0);
        $('#cria-total-tokens').html(0);
        $('#cria-cost').html('$0.00');

        var bot_id = $(this).data('bot_id');
        var prompt =
            $('#default-user-prompt').val() +
            $('#user-prompt').val();
        var content =
            $('#cria-test-input').val() ;
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
            console.log(result);
            let json = JSON.parse(result.stacktrace);
            $("#submit-question").show();
            $("#starting-process").hide();
            $('#cria-conversation').append().html(`${result.message}`);
            $('#cria-prompt-tokens').html(result.prompt_tokens);
            $('#cria-completion-tokens').html(result.completion_tokens);
            $('#cria-total-tokens').html(result.total_tokens);
            $('#cria-cost').html('$' + result.cost.toPrecision(6));
            $('#stacktrace').html('<pre>' + JSON.stringify(json, null, '\t') + '</pre>');
        }).fail(function (e) {
            alert(e.message + ' Your prompt contains unsupported characters. ' +
                'Please try again.');
            location.reload();
        });
    });
}