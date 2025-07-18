$(function() {
    var start = moment().subtract(29, 'days');
    var end = moment();
    var formSubmitted = false;
    var savedText = sessionStorage.getItem('savedText');

    if (savedText) {
        $('#reportrange span').text(savedText);
    } else {
        $('#reportrange span').text('Select a date');
    }

    function cb(start, end) {
        $('#start').val(start.format('YYYY-MM-DD'));
        $('#end').val(end.format('YYYY-MM-DD'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

    $('ul li').on("click", function() {
        let ulElement = document.querySelector('ul');
        let liElements = ulElement.querySelectorAll('li');

        $(liElements).removeClass('active');
        $(this).addClass('active');

        let text = $(this).text();
        $('#reportrange span').text(text);

        sessionStorage.setItem('savedText', text);
    });
    
    $('#filters-form').on('submit', function(e) {
        formSubmitted = true;
    });

    window.addEventListener('beforeunload', function (e) {
        if (!formSubmitted) {
            sessionStorage.setItem('savedText', 'Select a date'); 
        }
    });
});