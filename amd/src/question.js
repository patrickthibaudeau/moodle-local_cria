import $ from 'jquery';
import notification from 'core/notification';
import ajax from 'core/ajax';

export const init = () => {
    delete_question();
};

/**
 * Delete a content
 */
function delete_content() {

    $(".delete-question").off();
    $(".delete-question").on('click', function () {
        var id = $(this).data('id');

        notification.confirm('Delete',
            'Are you sure you want to delete this question? It cannot be recovered',
            'Delete',
            M.util.get_string('cancel', 'local_cria'), function () {
                //Delete the record
                var delete_content = ajax.call([{
                    methodname: 'cria_content_delete',
                    args: {
                        id: id
                    }
                }]);

                delete_content[0].done(function ($result) {
                    if ($result == 200) {
                        location.reload();
                    } else {
                        alert($result);
                    }
                }).fail(function () {
                    alert('An error has occurred. The record was not deleted');
                });
            });

    });
}