import $ from 'jquery';
import notification from 'core/notification';
import ajax from 'core/ajax';

export const init = () => {
    delete_question();
    select_deselect_questions();
    publish_questions();
    delete_all_questions();
};



/**
 * This function redirects the user to the 'edit_intent.php' page when an element with the class 'btn-edit-intent' is clicked.
 * It retrieves the 'id' and 'bot_id' from the clicked element and the '#bot-id' input field respectively, and includes them as parameters in the URL.
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
 * This function deletes a specific question when an element with the class 'delete-question' is clicked.
 * It sends an AJAX call to the 'cria_question_delete' method and handles the response, reloading the page if successful or displaying an error message if failed.
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

/**
 * This function selects or deselects all questions when the elements with ids 'select-all-questions' and 'deselect-all-questions' are clicked respectively.
 * It manipulates the 'checked' property of all checkboxes with the class 'question-select'.
 */
function select_deselect_questions() {
    // When select-questions element is clicked, verify if it is checked.
    // If it is, select all questions, otherwise deselect all questions.
    $('#select-questions').off();
    $('#select-questions').click(function () {
        if ($(this).prop('checked')) {
            $('.question-select').prop('checked', true);
        } else {
            $('.question-select').prop('checked', false);
        }
    });
}

/**
 * This function publishes selected questions when an element with the class 'publish-questions' is clicked.
 * It sends an AJAX call to the 'cria_question_publish' method for each selected question, removing the question from the page if successful or displaying an error message if failed.
 */
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

/**
 * This function deletes all questions related to a specific intent when an element with the class 'delete-all-questions' is clicked.
 * It sends an AJAX call to the 'cria_question_delete_all' method and handles the response, reloading the page if successful or displaying an error message if failed.
 */
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