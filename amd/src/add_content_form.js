import $ from 'jquery';
import notification from 'core/notification';
import ajax from 'core/ajax';
import select2 from 'local_cria/select2';

export const init = () => {
    $('#id_keywords').select2({
        'theme': 'classic',
        'width': '100%'
    });
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