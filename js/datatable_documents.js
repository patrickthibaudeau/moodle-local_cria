$(document).ready(function () {
    let wwwroot = M.cfg.wwwroot;

    let table = $('#cria-documents-table').DataTable({
        dom: 'lfrtip',
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": wwwroot + "/local/cria/ajax/datatable_documents.php",
            "type": "POST",
            "data": {
                "bot_id": $('#bot_id').val(),
                "intent_id": $('#intent_id').val()
            },
            "complete": function () {

                $('.delete-content').off();
                $('.delete-content').on('click', function () {
                    id = $(this).data('id');
                    // Insert title into modal
                    $('#cria-delete-modal-title').html('Document');
                    // Insert delete message into modal
                    $('#cria-delete-modal-message').html('Are you sure you want to delete this document?');
                    $('#cria-delete-modal').modal('toggle');
                    $('#cria-modal-delete-confirm').off();
                    $('#cria-modal-delete-confirm').on('click', function () {
                        $('#cria-delete-modal').modal('toggle');
                        $.ajax({
                            url: wwwroot + '/local/cria/ajax/delete_document.php?id=' + id,
                            type: 'POST',
                            success: function (results) {
                                let row = table.row($(this).parents('tr'));
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
    $('.dataTables_length').css('margin-top', '.5rem');
    $('.buttons-html5').addClass('btn-outline-primary');
    $('.buttons-html5').addClass('mr-2');
    $('.buttons-html5').removeClass('btn-secondary');

    $('#cria-document-select-all').on('click', function () {
        // if this element is checked, select all checkboxes .row-checkbox
        if ($(this).is(':checked')) {
            $('.cria-document-dt-select-box').prop('checked', true);
        } else {
            $('.cria-document-dt-select-box').prop('checked', false);
        }
    });

    // When icon-document-publish-all is clicked, publish all selected documents based on .cria-document-dt-select-box that are checked
    $('#cria-publish-all-files').on('click', function () {
        let selected = [];
        $('.cria-document-dt-select-box').each(function () {
            if ($(this).is(':checked')) {
                selected.push($(this).data('id'));
            }
        });
        // If no check boxes are selected, alert that no documents are selected else open the modal
        if (selected.length === 0) {
            alert('No documents selected. You  must select at least one document to publish.');
        } else {
            if (selected.length > 0) {
                $('#cria-publish-document-modal').modal('toggle');
                $('#cria-modal-publish-confirm').off();
                $('#cria-modal-publish-confirm').on('click', function () {
                    $('#cria-publish-modal').modal('toggle');
                    $.ajax({
                        url: wwwroot + '/local/cria/ajax/publish_documents.php',
                        type: 'POST',
                        data: {
                            'bot_id': $('#bot_id').val(),
                            'intent_id': $('#intent_id').val(),
                            'documents': selected
                        },
                        success: function (results) {
                            // Convert json into object
                            results = JSON.parse(results);
                            $('#cria-publish-document-modal').modal('toggle');
                            if (results.status === 404) {
                                alert(results.message);
                            } else {
                                table.ajax.reload();
                            }
                        }
                    });
                });
            }
        }
    });
});


//$('#ScheduledCoursesTable').DataTable({
//    dom: 'lrtip',
//    stateSave: true
//});

