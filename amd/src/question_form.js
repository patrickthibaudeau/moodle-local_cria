import $ from 'jquery';
import notification from 'core/notification';
import ajax from 'core/ajax';

export const init = () => {
    console.log('question_form init')
    edit_question_example();
};

/**
 * Delete a content
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