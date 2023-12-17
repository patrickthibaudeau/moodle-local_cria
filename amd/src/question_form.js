import $ from 'jquery';
import notification from 'core/notification';
import ajax from 'core/ajax';

export const init = () => {
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
        console.log(id);
        $(this).css('padding', '5px');

        $(this).on('keypress', function (e) {
            if (e.which == 13) {
                e.preventDefault();
                let value = $(this).text();
                console.log('id: ' + id + ' value: ' + value);
                // Update example question
                var update_content = ajax.call([{
                    methodname: 'cria_question_example_update',
                    args: {
                        id: id,
                        value: value
                    }
                }]);

                update_content[0].done(function ($result) {
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

function add_question_example() {
    $('.btn-add-question-example').off();
    $('.btn-add-question-example').on('click', function () {
        //Avoid the leave page message
        window.onbeforeunload = null;
        var question_id = $('[name="id"]').val();
        var question_text = $('#cria-question-example').val();

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
                        </td>
                        <td>
                            <span class="badge badge-pill badge-warning">Unpublished</span>
                            
                            <button type="button" class="btn btn-outline-danger delete-question-example" data-id="${id}">
                                Delete
                            </button>
                        </td>
                    </tr>` ;
            $('#cria-question-examples-table').append(html);
            delete_example_question();
        }).fail(function () {
            alert('An error has occurred. The question example was not saved.');
        });
    });
}