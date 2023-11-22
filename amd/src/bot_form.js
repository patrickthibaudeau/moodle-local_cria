import $ from 'jquery';
import notification from 'core/notification';
import ajax from 'core/ajax';
import ModalFactory from 'core/modal_factory';

export const init = () => {
    get_bot_type_message();
    get_model_max_tokens();
};

/**
 * Delete a content
 */
function get_bot_type_message() {

    $("#id_bot_type").off();
    $("#id_bot_type").on('change', function () {
        var id = $(this).val();
        var get_system_message = ajax.call([{
            methodname: 'cria_get_bot_type_message',
            args: {
                id: id
            }
        }]);

        get_system_message[0].done(function (result) {
            $('#id_bot_system_message').val('');
            $('#id_bot_system_message').val(result.trim());
        }).fail(function () {
            alert('An error has occurred. The record was not deleted');
        });

    });
}

function get_model_max_tokens() {

    $("#id_model_id").off();
    $("#id_model_id").on('change', function () {
        var id = $(this).val();
        var get_max_tokens = ajax.call([{
            methodname: 'cria_get_model_max_tokens',
            args: {
                id: id
            }
        }]);

        get_max_tokens[0].done(function (result) {
            $('#id_max_tokens').val('');
            $('#id_max_context').val('');
            $('#id_max_tokens').val(result);
            $('#id_max_context').val(result);
        }).fail(function () {
            alert('An error has occurred. The record was not deleted');
        });

    });
}