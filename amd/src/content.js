import $ from 'jquery';
import notification from 'core/notification';
import ajax from 'core/ajax';

export const init = () => {
    delete_content();
    delete_question();
    edit_intent();
    select_deselect_questions();
    publish_questions();
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

                delete_content[0].done(function ($result) {
                    if ($result == 200) {
                        location.reload();
                    } else {
                        alert($result + ' The file could not be found on the criadex server. However, the database link to the file has been deleted.');
                        location.reload();
                    }
                }).fail(function () {
                    alert('An error has occurred. The record was not deleted');
                });
            });

    });
}

/**
 * Edit an intent
 */
function edit_intent() {
    $(".btn-edit-intent").off();
    $(".btn-edit-intent").on('click', function () {
        var id = $(this).data('id');
        var bot_id = $('#bot-id').val();
        // Hyperlink to edit_intent.php
        window.location.href = M.cfg.wwwroot + '/local/cria/edit_intent.php?id=' + id + '&bot_id=' + bot_id;
    });
}

/**
 * Delete quetison
 */
function delete_question() {

    $(".delete-question").off();
    $(".delete-question").on('click', function () {
        var id = $(this).data('id');
        notification.confirm('Delete',
            'Are you sure you want to delete this question? The question and all examples cannot be recovered.',
            'Delete',
            M.util.get_string('cancel', 'local_cria'), function () {
                //Delete the record
                var delete_content = ajax.call([{
                    methodname: 'cria_question_delete',
                    args: {
                        id: id
                    }
                }]);

                delete_content[0].done(function ($result) {
                    if ($result == '200') {
                        location.reload();
                    } else {
                        alert($result);
                    }
                }).fail(function () {
                    alert('An error occured, the question could not be deleted.');
                });
            });

    });
}

function select_deselect_questions() {
    $('#select-all-questions').off();
    $('#select-all-questions').click(function () {
        $('input:checkbox.question-select').prop('checked', true);
    });

    $('#deselect-all-questions').click(function () {
        $('input:checkbox.question-select').prop('checked', false);
    });
}

function publish_questions() {
    $('.publish-questions').off();
    $('.publish-questions').click(function () {
        let button = $(this);
        button.text('Publishing question(s)...');
        $('input:checkbox.question-select:checked').each(function () {
            let input = $(this);
            var publish_questions = ajax.call([{
                methodname: 'cria_question_publish',
                args: {
                    id: $(this).data('id')
                }
            }]);
            publish_questions[0].done(function ($result) {
                if ($result == true) {
                    button.text('Publish question(s)');
                    input.remove();
                } else {
                    alert($result);
                }
            }).fail(function (e) {
                console.log(e);
                alert('An error occured, the question could not be published.');
            });
        });
    });
}