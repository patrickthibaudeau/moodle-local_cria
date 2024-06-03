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
    publish_urls();
};


/**
 * This function deletes a specific content when an element with the class 'delete-content' is clicked.
 * It sends an AJAX call to the 'cria_content_delete' method and handles the response, reloading the page if successful or displaying an error message if failed.
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
    $('#select-all-questions').off();
    $('#select-all-questions').click(function () {
        $('input:checkbox.question-select').prop('checked', true);
    });

    $('#deselect-all-questions').click(function () {
        $('input:checkbox.question-select').prop('checked', false);
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

/**
 * This function triggers the publishing of all documents related to a specific intent when the element with the id 'cria-publish-all-files' is clicked.
 * It sends an AJAX call to the 'cria_content_publish_files' method and handles the response, updating the HTML of the clicked element based on the success or failure of the AJAX call.
 */
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

/**
 * This function publishes URLs when the element with the id 'btn-cria-save-urls' is clicked.
 * It retrieves the URLs from the '#local-cria-urls' textarea and the 'intent_id' from the clicked element, sends an AJAX call to the 'cria_content_publish_urls' method with these as parameters, and handles the response, reloading the page if successful or displaying an error message if failed.
 */
function publish_urls() {
    $('#btn-cria-save-urls').on('click', function () {
        var urls = $('#local-cria-urls').val();
        var intent_id = $(this).data('intent_id');
        var publish_urls = ajax.call([{
            methodname: 'cria_content_publish_urls',
            args: {
                urls: urls,
                intent_id: intent_id
            }
        }]);
        publish_urls[0].done(function ($result) {
            location.reload();
        }).fail(function (e) {
                console.log(e);
                alert('An error occured, the URLs could not be published.');
            }
        );
    });
}