$(document).ready(function () {
    let wwwroot = M.cfg.wwwroot;

    let table = $('#cria-keywords-table').DataTable({
        dom: 'lfrtip',
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": wwwroot + "/local/cria/ajax/datatable_keywords.php",
            "type": "POST",
            "data": {
                "entity_id": $('#entity_id').val()
            },
            "complete": function () {
                $('.keyword-dt-delete').off();
                $('.keyword-dt-delete').on('click', function () {
                    id = $(this).data('id');
                    $('#cria-delete-modal').modal('toggle');
                    $('#cria-modal-delete-confirm').off();
                    $('#cria-modal-delete-confirm').on('click', function () {
                        $('#cria-delete-modal').modal('toggle');
                        $.ajax({
                            url: wwwroot + '/local/cria/ajax/delete_keyword.php?id=' + id,
                            type: 'POST',
                            success: function (results) {
                                let row = table.row($(this).parents('tr'));
                                row.remove()
                                    .draw(false);
                            }
                        });
                    });
                });

                // Edit entity
                $('.keyword-dt-edit').off();
                $('.keyword-dt-edit').on('click', function () {
                    let id = $(this).data('id');
                    window.location.href = wwwroot + '/local/cria/edit_keyword.php?id=' + id;
                });
            }
        },
        "deferRender": true,
        "columns": [
            {"data": "id"},
            {"data": "value"},
            {"data": "actions"}
        ],
        "order": [[0, "asc"]],
        "columnDefs": [
            {
                "targets": [2],
                "orderable": false
            },
            {
                "targets": [0],
                "visible": false,
                "searchable": false
            }
        ],
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, 10000], [10, 25, 50, 100, 500, 1000, 10000]],
        "pageLength": 25,
        stateSave: false
    });

    // Add some top spacing
    $('.dataTables_length').css('margin-top', '.5rem');
    $('.buttons-html5').addClass('btn-outline-primary');
    $('.buttons-html5').addClass('mr-2');
    $('.buttons-html5').removeClass('btn-secondary');

});


//$('#ScheduledCoursesTable').DataTable({
//    dom: 'lrtip',
//    stateSave: true
//});

