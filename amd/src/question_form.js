import $ from 'jquery';
import notification from 'core/notification';
import ajax from 'core/ajax';
import select2 from 'local_cria/select2';

export const init = () => {
    $('#id_keywords').select2({
        'theme': 'classic',
        'width': '100%'
    });
    edit_question_example();
    delete_example_question();
    add_question_example();
};

/**
 * Edit question example
 */
function edit_question_example() {
    $(".example-question").off();
    $(".example-question").on('click', function () {
        var id = $(this).data('id');
        let question = $(this)
        let row = $(this).closest('tr');

        $(this).css('padding', '5px');

        $(this).on('keypress', function (e) {
            if (e.which == 13) {
                e.preventDefault();
                let value = $(this).text();
                // Update example question
                var update_content = ajax.call([{
                    methodname: 'cria_question_example_update',
                    args: {
                        id: id,
                        value: value
                    }
                }]);

                update_content[0].done(function ($result) {
                    // Only add if span doesn't exist
                    if (row.find('td > span').length == 0) {
                        let html = '<span class="badge badge-pill badge-warning">Unpublished</span>';
                        question.closest('td').append(html);
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
function delete_example_question() {

    $(".delete-question-example").off();
    $(".delete-question-example").on('click', function () {
        let id = $(this).data('id');
        let row = $(this).closest('tr')

        notification.confirm('Delete',
            'Are you sure you want to delete this example question? The example question cannot be recovered.',
            'Delete',
            M.util.get_string('cancel', 'local_cria'), function () {
                //Delete the record
                var delete_content = ajax.call([{
                    methodname: 'cria_question_example_delete',
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
function add_question_example() {
    $('.btn-add-question-example').off();
    $('.btn-add-question-example').on('click', function () {
        // Get question id
        var question_id = $('[name="id"]').val();
        var question_text = $('#cria-question-example').val();
        // Add question example
        var add_question_example = ajax.call([{
            methodname: 'cria_question_example_insert',
            args: {
                questionid: question_id,
                value: question_text,
            }
        }]);

        add_question_example[0].done(function ($result) {
            let id = $result;
            let html = `<tr>
                        <td>
                            <div class="example-question" style="cursor: pointer;" contenteditable="true"
                                 data-id="${id}">${question_text}</div>
                                 <span class="badge badge-pill badge-warning">Unpublished</span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-danger delete-question-example" data-id="${id}">
                                Delete
                            </button>
                        </td>
                    </tr>` ;
            // Append new question example
            $('#cria-question-examples-table').append(html);
            // Reinitalize events
            delete_example_question();
            edit_question_example();
        }).fail(function () {
            alert('An error has occurred. The question example was not saved.');
        });
    });
}