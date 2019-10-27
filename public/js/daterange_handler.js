$(document).ready(function() {
    var start = $('#results_filter_dateFrom');
    var end = $('#results_filter_dateTo');

    start.datepicker({
        format: 'dd.mm.yyyy',
        language: '{{ app.request.locale }}'
    });

    end.datepicker({
        format: 'dd.mm.yyyy',
        language: '{{ app.request.locale }}'
    });

    $('#for-a-month').on('click', function (event) {
        event.preventDefault();
        moveDatesForPeriod(null, 1);
    });

    $('#for-six-months').on('click', function (event) {
        event.preventDefault();
        moveDatesForPeriod(null, 6);
    });

    $('#for-a-year').on('click', function (event) {
        event.preventDefault();
        moveDatesForPeriod(null, 12);
    });

    $('#reset').on('click', function (event) {
        event.preventDefault();
        start.datepicker('clearDates');
        end.datepicker('clearDates');
    });

    function moveDatesForPeriod(startMonth = null, endMonth = null) {
        event.preventDefault();

        var startDate = new Date();
        var endDate = new Date();

        endDate.setDate(1);

        endDate.setMonth(endDate.getMonth() - endMonth);

        start.datepicker('setDate', endDate);
        end.datepicker('setDate', startDate);
    }
});
