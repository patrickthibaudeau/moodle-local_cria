import $ from 'jquery';
import notification from 'core/notification';
import ajax from 'core/ajax';
import ModalFactory from 'core/modal_factory';

export const init = () => {
    delete_bot();
    share_bot();
};

/**
 * Delete a content
 */
function delete_bot() {

    $(".delete-bot").off();
    $(".delete-bot").on('click', function () {
        var id = $(this).data('id');

        notification.confirm('Delete',
            'Are you sure you want to delete this bot? All content will also be deleted. It will not be possible to recover.',
            'Delete',
            M.util.get_string('cancel', 'local_cria'), function () {
                //Delete the record
                var delete_content = ajax.call([{
                    methodname: 'cria_bot_delete',
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

function share_bot() {
    $(".share-bot").off();
    $(".share-bot").on('click', function () {
        let bot_id = $(this).data('id');
        let share = $(this).data('share');
        let theme = $(this).data('theme');
        theme = theme.replace(/\#/g, "");
        $('#share-code').html(`<code> \t&lt;script type="text/javascript" src="${share}/${bot_id}/load" async&gt; \t&lt;/script&gt;</code>`);
        $('#shareModal').modal('show');
    });
}