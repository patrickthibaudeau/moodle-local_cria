import $ from 'jquery';
import notification from 'core/notification';
import ajax from 'core/ajax';

export const init = () => {
    edit_synonym();
    delete_synonym();
    add_synonym();
};

/**
 * Edit question example
 */
function edit_synonym() {
    $(".edit-synonym").off();
    $(".edit-synonym").on('click', function () {
        var id = $(this).data('id');
        let synonym = $(this)
        let row = $(this).closest('tr');

        $(this).css('padding', '5px');

        $(this).on('keypress', function (e) {
            if (e.which == 13) {
                e.preventDefault();
                let value = $(this).text();
                // Update example question
                var update_content = ajax.call([{
                    methodname: 'cria_synonym_update',
                    args: {
                        id: id,
                        value: value
                    }
                }]);

                update_content[0].done(function ($result) {
                    // Only add if span doesn't exist
                    if (row.find('td > span').length == 0) {
                        let html = '<span class="badge badge-pill badge-success">Saved</span>';
                        synonym.closest('td').append(html);
                    }
                    // Remove focus
                    question.blur();
                    $('id_save').focus();
                }).fail(function () {
                    alert('An error has occurred. The question was not saved.');
                });
            }
        });


    });
}

/**
 * Delete example question
 */
function delete_synonym() {

    $(".delete-synonym").off();
    $(".delete-synonym").on('click', function () {
        let id = $(this).data('id');
        let row = $(this).closest('tr')

        notification.confirm('Delete',
            'Are you sure you want to delete this synonym?',
            'Delete',
            M.util.get_string('cancel', 'local_cria'), function () {
                //Delete the record
                var delete_content = ajax.call([{
                    methodname: 'cria_synonym_delete',
                    args: {
                        id: id
                    }
                }]);
                delete_content[0].done(function ($result) {
                    row.remove();
                }).fail(function () {
                    alert('An error has occurred. The question example was not saved.');
                });
            });
    });
}

/**
 * Add question example
 */
function add_synonym() {
    $('.btn-add-synonym').off();
    $('.btn-add-synonym').on('click', function () {
        // Get question id
        var keyword_id = $('[name="id"]').val();
        var synonym_text = $('#cria-synonym').val();
        // Add question example
        var add_synonym = ajax.call([{
            methodname: 'cria_synonym_insert',
            args: {
                keyword_id: keyword_id,
                value: synonym_text,
            }
        }]);

        add_synonym[0].done(function ($result) {
            let id = $result;
            let html = `<tr>
                        <td>
                            <div class="edit-synonym" style="cursor: pointer;" contenteditable="true"
                                 data-id="${id}">${synonym_text}</div>
                                 <span class="badge badge-pill badge-success">Saved</span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-danger delete-synonym" data-id="${id}">
                                Delete
                            </button>
                        </td>
                    </tr>` ;
            // Append new question example
            $('#cria-synonyms-table').append(html);
            // Reinitalize events
            delete_synonym();
            edit_synonym();
        }).fail(function () {
            alert('An error has occurred. The synonym was not saved.');
        });
    });
}