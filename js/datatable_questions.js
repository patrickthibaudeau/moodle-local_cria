$(document).ready(function () {
    var wwwroot = M.cfg.wwwroot;

    let question_table = $('#cria-questions-table').DataTable({
        dom: 'lfrtip',
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": wwwroot + "/local/cria/ajax/datatable_questions.php",
            "type": "POST",
            "data": {
                "bot_id": $('#bot_id').val(),
                "intent_id": $('#intent_id').val()
            },
            "complete": function () {

                $('.delete-question').off();
                $('.delete-question').on('click', function () {
                    id = $(this).data('id');
                    // Insert title into modal
                    $('#cria-delete-modal-title').html('Question');
                    // Insert delete message into modal
                    $('#cria-delete-modal-message').html('Are you sure you want to delete this question?');
                    $('#cria-delete-modal').modal('toggle');
                    $('#cria-modal-delete-confirm').off();
                    $('#cria-modal-delete-confirm').on('click', function () {
                        $('#cria-delete-modal').modal('toggle');
                        $.ajax({
                            url: wwwroot + '/local/cria/ajax/delete_question.php?id=' + id,
                            type: 'POST',
                            success: function (results) {
                                let row = question_table.row($(this).parents('tr'));
                                row.remove()
                                    .draw(false);
                            }
                        });
                    });
                });
                //
                // // Edit entity
                // $('.keyword-dt-edit').off();
                // $('.keyword-dt-edit').on('click', function () {
                //     let id = $(this).data('id');
                //     window.location.href = wwwroot + '/local/cria/edit_keyword.php?id=' + id;
                // });
            }
        },
        "deferRender": true,
        "columns": [
            {
                "data": "select",
            },
            {"data": "name"},
            {"data": "actions"}
        ],
        "order": [[1, "asc"]],
        "columnDefs": [
            {
                "targets": [0, 2],
                "orderable": false
            },
            {
                "targets": [0],
                "visible": true,
                "searchable": false
            }
        ],
        "lengthMenu": [[5, 10, 25, 50, 100, 500, 1000, 10000], [5, 10, 25, 50, 100, 500, 1000, 10000]],
        "pageLength": 25,
        stateSave: false
    });

    // Add some top spacing
    // $('.dataTables_length').css('margin-top', '.5rem');
    // $('.buttons-html5').addClass('btn-outline-primary');
    // $('.buttons-html5').addClass('mr-2');
    // $('.buttons-html5').removeClass('btn-secondary');

    $('#cria-question-select-all').on('click', function () {
        // if this element is checked, select all checkboxes .row-checkbox
        if ($(this).is(':checked')) {
            $('.cria-question-dt-select-box').prop('checked', true);
        } else {
            $('.cria-question-dt-select-box').prop('checked', false);
        }
    });

    // When cria-publish-question is clicked, publish all selected documents based on .cria-document-dt-select-box that are checked
    $('.cria-publish-questions').off();
    $('.cria-publish-questions').on('click', function () {
        let selected = [];
        $('.cria-question-dt-select-box').each(function () {
            if ($(this).is(':checked')) {
                selected.push($(this).data('id'));
            }
        });
        // If no check boxes are selected, alert that no documents are selected else open the modal
        if (selected.length === 0) {
            alert('No questions selected. You  must select at least one question to publish.');
        } else {
            if (selected.length > 0) {
                $('#cria-publish-question-modal').modal('toggle');
                $('#cria-modal-publish-question-confirm').off();
                $('#cria-modal-publish-question-confirm').on('click', function () {
                    document.getElementById('cria-loader').style.display = 'flex';
                    $('#cria-publish-question-modal').modal('toggle');
                    $.ajax({
                        url: wwwroot + '/local/cria/ajax/publish_question.php',
                        type: 'POST',
                        data: {
                            'bot_id': $('#bot_id').val(),
                            'intent_id': $('#intent_id').val(),
                            'questions': selected
                        },
                        success: function (results) {
                            // Convert json into object
                            results = JSON.parse(results);
                            // Hide the loader
                            document.getElementById('cria-loader').style.display = 'none';
                            if (results.status === 404) {
                                alert(results.message);
                            } else {
                                question_table.ajax.reload();
                            }
                        }
                    });
                });
            }
        }
    });

    // When criaDeleteSelectedQuestions is clicked, delete all selected questions based on .cria-question-dt-select-box that are checked
    $('#criaDeleteSelectedQuestions').off();
    $('#criaDeleteSelectedQuestions').on('click', function () {
        let selected = [];
        $('.cria-question-dt-select-box').each(function () {
            if ($(this).is(':checked')) {
                selected.push($(this).data('id'));
            }
        });
        // If no check boxes are selected, alert that no questions are selected else open the modal
        if (selected.length === 0) {
            alert('No questions selected. You  must select at least one question to delete.');
        } else {
            if (selected.length > 0) {
                $('#cria-delete-modal-title').html('Question');
                $('#cria-delete-modal-message').html('Are you sure you want to delete these questions?');
                $('#cria-delete-modal').modal('toggle');
                $('#cria-modal-delete-confirm').off();
                $('#cria-modal-delete-confirm').on('click', function () {
                    $('#cria-delete-modal').modal('toggle');
                    document.getElementById('cria-loader').style.display = 'flex';
                    $.ajax({
                        url: wwwroot + '/local/cria/ajax/delete_question.php',
                        type: 'POST',
                        data: {
                            'questions': selected
                        },
                        success: function (results) {
                            document.getElementById('cria-loader').style.display = 'none';
                            question_table.ajax.reload();
                        }
                    });
                });
            }
        }
    });
});


