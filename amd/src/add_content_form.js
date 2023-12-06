import $ from 'jquery';
import notification from 'core/notification';
import ajax from 'core/ajax';

export const init = () => {
    process_content();
};

/**
 * Delete a content
 */
function process_content() {
    $("#id_submitbutton").off();
    $("#id_submitbutton").on('click', function () {
        var id = $('[name="bot_id"]').val();
        $('#starting-process').show();
        setTimeout(function () {
            $('#starting-process').hide();
            $('#almost-done').show();
        }, 4000);
        setTimeout(function () {
            $('#almost-done').hide();
            $('#process-complete').show();
        }, 20000);
    });
}