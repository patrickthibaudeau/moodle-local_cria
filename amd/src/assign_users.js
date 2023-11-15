import $ from 'jquery';
import ajax from 'core/ajax';
import Templates from 'core/templates';
import select2 from './select2';

export const init = () => {
    initialize_modal();
}

function initialize_modal() {
    $('.btn-assign-users').off();
    $('.btn-assign-users').on('click', function () {
        let role_id = $(this).data('roleid');
        let bot_id = $(this).data('botid');
        let role_name = $(this).data('rolename');
        // Set modal title
        $('#cria-modal-title').html('Assign Users to ' + role_name);
        $('#roleid').val(role_id);
        $('#botid').val(bot_id);
        // Using ajax, add all users to assigned users list
        get_assigned_users(role_id);
        // Using ajax, get all users
        get_all_users(role_id);
        // Assign users
        assign_users();
        // Unassign users
        unassign_users();
        // Search assigned users on keyup
        $('#cria-search-unassigned-user').on('keyup', function () {
            let name = $(this).val();
            if (name.length == 0) {
                get_all_users(role_id);
            }
            if (name.length >= 3) {
                get_all_users(role_id, name);
            }
        });
        // Show modal
        $("#assign-users-modal").modal("show");

    });
}

/**
 * Get Assinged users
 */
function get_assigned_users(role_id) {
    $('#cria-assigned-users').empty();
    var assigned_users = ajax.call([{
        methodname: 'cria_get_assigned_users',
        args: {
            id: role_id
        }
    }]);

    assigned_users[0].done(function (results) {
        $.each(results, function (index, value) {
            $('#cria-assigned-users').append($('<option>', {
                value: value.id,
                text: value.firstname + ' ' + value.lastname
            }));
        });
    }).fail(function (error) {
        console.log(error);
        alert('An error has occurred.');
    });
}

/**
 * Get all users
 */
function get_all_users(role_id, name = '') {
    $('#cria-unassigned-users').empty();
    // Using ajax, get all users
    var all_users = ajax.call([{
        methodname: 'cria_get_users',
        args: {
            role_id: role_id,
            id: -1,
            name: name
        }
    }]);

    all_users[0].done(function (all_users_results) {
        $.each(all_users_results, function (index, value) {
            $('#cria-unassigned-users').append($('<option>', {
                value: value.id,
                text: value.firstname + ' ' + value.lastname
            }));
        });
    }).fail(function () {
        alert('An error has occurred.');
    });
}

/**
 * Assign users
 */
function assign_users() {
    $('#cria-assign-users').off();
    $('#cria-assign-users').on('click', function () {
        let role_id = $('#roleid').val();
        // Get all selected options from cria-unassgined-users
        var selected_users = [];
        $('#cria-unassigned-users :selected').each(function (i, selected) {
            var assign_users = ajax.call([{
                methodname: 'cria_assign_user_role',
                args: {
                    role_id: role_id,
                    user_id: $(selected).val()
                }
            }]);

            assign_users[0].done(function (results) {
            }).fail(function () {
                alert('An error has occurred.');
            });
        });
        // Set a delay, otherwise the refresh does not happen
        setTimeout(function () {
            get_all_users(role_id);
        }, 500);
        setTimeout(function () {
            get_assigned_users(role_id);
        }, 500);
    });
}

/**
 * Unassign users
 */
function unassign_users() {
    $('#cria-unassign-users').off();
    $('#cria-unassign-users').on('click', function () {
        let role_id = $('#roleid').val();
        // Get all selected options from cria-unassgined-users
        var selected_users = [];
        $('#cria-assigned-users :selected').each(function (i, selected) {
            var unassign_users = ajax.call([{
                methodname: 'cria_unassign_user_role',
                args: {
                    id: $(selected).val()
                }
            }]);

            unassign_users[0].done(function (results) {
            }).fail(function () {
                alert('An error has occurred.');
            });
        });
        // Set a delay, otherwise the refresh does not happen
        setTimeout(function () {
            get_all_users(role_id);
        }, 500);
        setTimeout(function () {
            get_assigned_users(role_id);
        }, 500);

    });
}