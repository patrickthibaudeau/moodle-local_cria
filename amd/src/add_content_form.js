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
        document.getElementById('cria-loader').style.display = 'flex';
    });
}