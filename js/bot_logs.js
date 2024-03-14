$(document).ready(function () {
// Date range
    $('#cria-date-picker').daterangepicker({
        autoApply: true
    }).on('change', function () {
       var bot_id = $(this).data('bot_id');
       var date_range = $(this).val();
       window.location = 'bot_logs.php?bot_id=' + bot_id + '&daterange=' + date_range;
    });
});