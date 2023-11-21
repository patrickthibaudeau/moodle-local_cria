import $ from 'jquery';
import notification from 'core/notification';
import ajax from 'core/ajax';
import ModalFactory from 'core/modal_factory';

export const init = () => {
    delete_model();
    select_provider();
};

/**
 * Delete a content
 */
function delete_model() {

    $(".delete-model").off();
    $(".delete-model").on('click', function () {
        var id = $(this).data('id');

        notification.confirm('Delete',
            'Are you sure you want to delete this model?',
            'Delete',
            M.util.get_string('cancel', 'local_cria'), function () {
                //Delete the record
                var delete_model = ajax.call([{
                    methodname: 'cria_model_delete',
                    args: {
                        id: id
                    }
                }]);

                delete_content[0].done(function () {
                    location.reload();
                }).fail(function () {
                    alert('An error has occurred. The record was not deleted');
                });
            });

    });
}

function select_provider() {
    $("#cria-add-model").off();
    $("#cria-add-model").on('click', function () {

        $('#cria-provider-modal').modal('show');

        $('.btn-select-provider').off();
        $('.btn-select-provider').on('click', function () {
            var idnumber = $(this).data('idnumber');
            window.location.href = M.cfg.wwwroot + '/local/cria/providers/' + idnumber + '/model.php';
        });
    });
}