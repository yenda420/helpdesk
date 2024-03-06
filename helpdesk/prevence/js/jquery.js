$(document).ready(function() {
    //User has to select at least one department:
    $('#adminForm').on('submit', function(e) {
        if ($('.department:checked').length == 0) {
            e.preventDefault();
            alert('Please select at least one department.');
        }
    });
});