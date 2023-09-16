import $ from 'jquery';
import notification from 'core/notification';
import ajax from 'core/ajax';

export const init = () => {
    delete_content();
};

/**
 * Delete a content
 */
function delete_content() {

    $(".delete-content").off();
    $(".delete-content").on('click', function () {
        var id = $(this).data('id');

        notification.confirm('Delete',
            'Are you sure you want to delete this content? It cannot be recovered',
            'Delete',
            M.util.get_string('cancel', 'local_cria'), function () {
                //Delete the record
                var delete_content = ajax.call([{
                    methodname: 'cria_content_delete',
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