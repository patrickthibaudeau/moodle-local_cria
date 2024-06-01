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

/**
 * This function is responsible for publishing selected questions.
 * It first selects all HTML elements with the class 'publish-questions'.
 * It defines a function `publishQuestionsFunction` which will be triggered when a 'publish-questions' element is clicked.
 * Inside `publishQuestionsFunction`, it selects all checked checkboxes with the class 'question-select'.
 * For each of these checked checkboxes, it retrieves the 'data-id' attribute and makes an AJAX call to the 'cria_question_publish' method with the id as an argument.
 * If the AJAX call is successful and returns true, it removes the input element (checkbox) from the DOM.
 * If the AJAX call is not successful, it shows an alert message to the user with the result of the AJAX call.
 * If the AJAX call fails, it logs the error and shows an alert message to the user indicating that an error occurred and the question could not be published.
 * Finally, for each 'publish-questions' element, it sets up a click event listener that triggers the `publishQuestionsFunction`.
 */
function publish_questions() {
    // Select all HTML elements with the class 'publish-questions'
    var publishQuestionsElements = document.querySelectorAll('.publish-questions');

// Define a function that will be triggered when a 'publish-questions' element is clicked
    var publishQuestionsFunction = function() {
        // Select all checked checkboxes with the class 'question-select'
        var questionSelectCheckedElements = document.querySelectorAll('input.question-select:checked');

        // For each of these checked checkboxes
        questionSelectCheckedElements.forEach(function(input) {
            // Retrieve the 'data-id' attribute
            var id = input.getAttribute('data-id');

            // Make an AJAX call to the 'cria_question_publish' method with the id as an argument
            var publish_questions = ajax.call([{
                methodname: 'cria_question_publish',
                args: {
                    id: id
                }
            }]);

            // If the AJAX call is successful and returns true
            publish_questions[0].done(function ($result) {
                console.log($result);
                if ($result == true) {
                    // Remove the input element (checkbox) from the DOM
                    input.remove();
                } else {
                    // Show an alert message to the user with the result of the AJAX call
                    alert($result);
                }
            }).fail(function (e) {
                // Log the error and show an alert message to the user indicating that an error occurred and the question could not be published
                console.log(e);
                alert('An error occured, the question could not be published.');
            });
        });
    };

// For each 'publish-questions' element
    publishQuestionsElements.forEach(function(element) {
        // Remove any existing click event listener
        element.removeEventListener('click', publishQuestionsFunction);

        // Set up a click event listener that triggers the `publishQuestionsFunction`
        element.addEventListener('click', publishQuestionsFunction);
    });
}

/**
 * This function is responsible for deleting all questions related to a specific category.
 * It first selects all HTML elements with the class 'delete-all-questions'.
 * For each of these elements, it sets up a click event listener.
 * When an element is clicked, it retrieves the 'data-intent_id' attribute from the clicked element.
 * It then asks the user for confirmation to delete all questions for this category, warning that the questions and all examples cannot be recovered.
 * If the user confirms, it makes an AJAX call to the 'cria_question_delete_all' method with the intent_id as an argument.
 * If the AJAX call is successful and returns 200, it reloads the page.
 * If the AJAX call is not successful, it shows an alert message to the user indicating that an error occurred and the questions could not be deleted.
 * If the AJAX call fails, it also logs the error and shows an alert message to the user.
 */
function delete_all_questions() {
    // Select all HTML elements with the class 'delete-all-questions'
    var deleteAllQuestionsElement = document.querySelectorAll('.delete-all-questions');

// Define a function that will be triggered when a 'delete-all-questions' element is clicked
    var deleteAllQuestionsFunction = function() {
        // Retrieve the 'data-intent_id' attribute from the clicked element
        let intent_id = this.getAttribute('data-intent_id');

        // Ask the user for confirmation to delete all questions for this category
        if (confirm('Are you sure you want to delete all questions for this category? The questions and all examples cannot be recovered.')) {
            // If the user confirms, make an AJAX call to the 'cria_question_delete_all' method with the intent_id as an argument
            var delete_all_questions = ajax.call([{
                methodname: 'cria_question_delete_all',
                args: {
                    "intent_id": intent_id
                }
            }]);

            // If the AJAX call is successful and returns 200, reload the page
            delete_all_questions[0].done(function ($result) {
                if ($result == 200) {
                    location.reload();
                } else {
                    // If the AJAX call is not successful, show an alert message to the user indicating that an error occurred and the questions could not be deleted
                    alert('An error occured, the questions could not be deleted.');
                }
            }).fail(function (e) {
                // If the AJAX call fails, log the error and show an alert message to the user
                console.log(e);
                alert('An error occured, the questions could not be deleted.');
            });
        }
    };

// For each 'delete-all-questions' element
    deleteAllQuestionsElement.forEach(function(element) {
        // Remove any existing click event listener
        element.removeEventListener('click', deleteAllQuestionsFunction);

        // Set up a click event listener that triggers the `deleteAllQuestionsFunction`
        element.addEventListener('click', deleteAllQuestionsFunction);
    });
}

/**
 * This function is responsible for publishing all documents.
 * It first selects the necessary HTML elements by their IDs.
 * When the 'cria-publish-all-files' element is clicked, it hides the 'icon-document-publish-all' element and shows the 'icon-document-publish-all-spinner' element.
 * It then retrieves the 'data-intent_id' attribute from the clicked element and makes an AJAX call to the 'cria_content_publish_files' method with the intent_id as an argument.
 * If the AJAX call is successful, it hides the spinner and shows the publish icon again.
 * If the AJAX call fails, it also hides the spinner and shows the publish icon, but also logs the error and shows an alert message to the user.
 */
function publish_all_documents() {
// Get the element with the ID 'cria-publish-all-files'
    var publishAllFilesElement = document.getElementById('cria-publish-all-files');

// Get the element with the ID 'icon-document-publish-all'
    var documentPublishAllElement = document.getElementById('icon-document-publish-all');

// Get the element with the ID 'icon-document-publish-all-spinner'
    var documentPublishAllSpinnerElement = document.getElementById('icon-document-publish-all-spinner');

// Add a click event listener to the 'publishAllFilesElement'
    publishAllFilesElement.addEventListener('click', function() {
        // Hide the 'documentPublishAllElement' when the button is clicked
        documentPublishAllElement.style.display = 'none';

        // Show the 'documentPublishAllSpinnerElement' when the button is clicked
        documentPublishAllSpinnerElement.style.display = 'block';

        // Get the 'data-intent_id' attribute from the clicked element
        var intent_id = this.getAttribute('data-intent_id');

        // Make an AJAX call to the 'cria_content_publish_files' method with the intent_id as an argument
        var publish_files = ajax.call([{
            methodname: 'cria_content_publish_files',
            args: {
                intent_id: intent_id
            }
        }]);

        // If the AJAX call is successful
        publish_files[0].done(function ($result) {
            // Show the 'documentPublishAllElement' again
            documentPublishAllElement.style.display = 'block';

            // Hide the 'documentPublishAllSpinnerElement'
            documentPublishAllSpinnerElement.style.display = 'none';
        }).fail(function (e) {
            // If the AJAX call fails, show the 'documentPublishAllElement' and hide the 'documentPublishAllSpinnerElement'
            documentPublishAllElement.style.display = 'block';
            documentPublishAllSpinnerElement.style.display = 'none';

            // Log the error
            console.log(e);

            // Show an alert message to the user indicating that an error occurred and the question could not be published
            alert('An error occured, the question could not be published.');
        });
    });
}