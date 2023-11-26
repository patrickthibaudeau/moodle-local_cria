import $ from 'jquery';
import notification from 'core/notification';
import ajax from 'core/ajax';
import ModalFactory from 'core/modal_factory';

export const init = () => {
    get_bot_type_message();
    get_model_max_tokens();
    set_tone_parameters();
    set_length_parameters();
};

/**
 * Delete a content
 */
function get_bot_type_message() {

    $("#id_bot_type").off();
    $("#id_bot_type").on('change', function () {
        var id = $(this).val();
        var get_system_message = ajax.call([{
            methodname: 'cria_get_bot_type_message',
            args: {
                id: id
            }
        }]);

        get_system_message[0].done(function (result) {
            if ($('#id_bot_system_message').length) {
                $('#id_bot_system_message').val('');
                $('#id_bot_system_message').val(result.trim());
            }

            if ($('[name="bot_system_message"]').length) {
                $('[name="bot_system_message"]').val('');
                $('[name="bot_system_message"]').val(result.trim());
            }

        }).fail(function () {
            alert('An error has occurred. The record was not deleted');
        });

    });
}

function get_model_max_tokens() {

    $("#id_model_id").off();
    $("#id_model_id").on('change', function () {
        var id = $(this).val();

        if (id != '') {
            var get_max_tokens = ajax.call([{
                methodname: 'cria_get_model_max_tokens',
                args: {
                    id: id
                }
            }]);

            get_max_tokens[0].done(function (result) {
                if ($('[name="max_tokens"]').length) {
                    $('[name="max_tokens"]').val('');
                    $('[name="max_tokens"]').val(result);
                }


                if ($('[name="max_context"]').length) {
                    $('[name="max_context"]').val('');
                    $('[name="max_context"]').val(result);
                    // if .btn-short is active adjsut max_tokens
                    if ($('.btn-short').hasClass('active')) {
                        $('[name="max_tokens"]').val(result / 8);
                    }
                    // if .btn-medium is active adjsut max_tokens
                    if ($('.btn-medium').hasClass('active')) {
                        $('[name="max_tokens"]').val(result / 2);
                    }
                    // if .btn-long is active adjsut max_tokens
                    if ($('.btn-long').hasClass('active')) {
                        $('[name="max_tokens"]').val(result - 2000);
                    }
                }


            }).fail(function () {
                alert('An error has occurred. The record was not deleted');
            });
            $('#cria-tone-buttons').show();
        } else {
            $('#cria-tone-buttons').hide();
        }
    });
}

/**
 * Update the tone parameters
 */
function set_tone_parameters() {

    $('.btn-gpt-tone').off();
    $('.btn-gpt-tone').on('click', function () {
        // remove class active for all buttons with class .btn-gpt-tone
        $('.btn-gpt-tone').removeClass('active');
        // Add class active to this button
        $(this).addClass('active');
        // Update id_temperature
        $('[name="temperature"]').val($(this).data('temperature'));
        // Update id_top_p
        $('[name="top_p"]').val($(this).data('top_p'));
        // pdate top_k
        $('[name="top_k"]').val($(this).data('top_k'));
        // pdate min_relevance
        $('[name="min_relevance"]').val($(this).data('min_relevance'));
    });
}

/**
 * Set length paramters
 */
function set_length_parameters() {
    let max_tokens = 0;
    // Set button short
    $('.btn-short').off();
    $('.btn-short').on('click', function () {
        max_tokens = $('[name="max_context"]').val();
        $('[name="max_tokens"]').val(max_tokens / 8);
        $('.btn-medium').removeClass('active');
        $('.btn-long').removeClass('active');
        $(this).addClass('active');
    });
    // Set button medium
    $('.btn-medium').off();
    $('.btn-medium').on('click', function () {
        max_tokens = $('[name="max_context"]').val();
        $('[name="max_tokens"]').val(max_tokens / 2);
        $('.btn-short').removeClass('active');
        $('.btn-long').removeClass('active');
        $(this).addClass('active');
    });
    // Set button long
    $('.btn-long').off();
    $('.btn-long').on('click', function () {
        max_tokens = $('[name="max_context"]').val();
        // reduce by 200 tokens to make sure all content can be used.
        $('[name="max_tokens"]').val(max_tokens - 2000);
        $('.btn-short').removeClass('active');
        $('.btn-medium').removeClass('active');
        $(this).addClass('active');
    });
}

