$(document).ready(function () {
// Date range
    $('#cria-date-picker').daterangepicker({
        autoApply: true
    }).on('change', function () {
       var bot_id = $(this).data('bot_id');
       var date_range = $(this).val();
       window.location = 'bot_logs.php?bot_id=' + bot_id + '&daterange=' + date_range;
    });

    $('#bot-logs-table').DataTable({
        dom: 'Blfrtip',
        buttons: [
            'csvHtml5',
            'excelHtml5',
        ],
        theme: 'bootstrap4',

    });

    // Add some top spacing
    $('.dataTables_length').css('margin-top', '.5rem');

    $('.buttons-html5').addClass('btn-outline-primary');
    $('.buttons-html5').removeClass('btn-secondary');

});