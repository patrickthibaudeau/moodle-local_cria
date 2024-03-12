import $ from 'jquery';
import notification from 'core/notification';
import ajax from 'core/ajax';

export const init = () => {
    delete_content();
    delete_question();
    edit_intent();
    select_deselect_questions();
    publish_questions();
    delete_all_questions();
    publish_all_documents();
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
                    location.reload();
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

function delete_all_questions() {
    $('.delete-all-questions').off();
    $('.delete-all-questions').click(function () {
        let intent_id = $(this).data('intent_id');
        notification.confirm('Delete',
            'Are you sure you want to delete all questions for this category? The questions and all examples cannot be recovered.',
            'Delete',
            M.util.get_string('cancel', 'local_cria'), function () {
                //Delete all records
                var delete_all_questions = ajax.call([{
                    methodname: 'cria_question_delete_all',
                    args: {
                        "intent_id": intent_id
                    }
                }]);
                delete_all_questions[0].done(function ($result) {
                    if ($result == 200) {
                        location.reload();
                    } else {
                        alert('An error occured, the questions could not be deleted.');
                    }
                }).fail(function (e) {
                    console.log(e);
                    alert('An error occured, the questions could not be deleted.');
                });
            });
    });
}


function publish_all_documents() {
    $('#cria-publish-all-files').click(function () {
        $(this).html('');
        $(this).html('<i class="bi bi-arrow-repeat gly-spin"></i>');
        var intent_id = $(this).data('intent_id');
        console.log(intent_id);
        var publish_files = ajax.call([{
            methodname: 'cria_content_publish_files',
            args: {
                intent_id: intent_id
            }
        }]);
        publish_files[0].done(function ($result) {
            $('#cria-publish-all-files').html('<i class="bi bi-cloud-upload"></i>');
        }).fail(function (e) {
            console.log(e);
            alert('An error occured, the question could not be published.');
        });

    });
}