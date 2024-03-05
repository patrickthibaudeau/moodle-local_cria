import $ from 'jquery';
import ajax from 'core/ajax';

export const init = () => {
    get_response();
    page_unload();
};

/**
 * Delete a content
 */
function get_response() {

    $("#submit-question").off();
    $("#submit-question").on('click', function () {
        perform_request();
    });

    $('#user-prompt').keypress(function (e) {
        if (e.which == 13) { // Check if the key code is for Enter
            perform_request();
            return false; // Prevent the default form submission
        }
    });
}

function perform_request() {
    // rest statistics
    $('#cria-conversation').html('');
    $('#cria-prompt-tokens').html(0);
    $('#cria-completion-tokens').html(0);
    $('#cria-total-tokens').html(0);
    $('#cria-cost').html('$0.00');

    var bot_id = $("#submit-question").data('bot_id');
    var prompt =
        $('#default-user-prompt').val() +
        $('#user-prompt').val();
    var content =
        $('#cria-test-input').val();
    var chat_id = $('#cria-chat-id').val();
    // COnvert chat_id to string
    if (chat_id == 0) {
        chat_id = 'none';
    }
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
        $('#cria-file-name').html(result.file_name);
    }).fail(function (e) {
        alert(e.message + ' Your prompt contains unsupported characters. ' +
            'Please try again.');
        location.reload();
    });
}

function page_unload() {
    window.addEventListener('beforeunload', function (e) {
        e.preventDefault();
        e.returnValue = '';
        alert('Please wait for the process to complete.');

    });
}