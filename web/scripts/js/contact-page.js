$(document).ready(function() {
    $('#appointment_form_withUser').prop("disabled", true);

    // this way the input will be send
    $("#appointmentForm").on('submit', function(e) {
    	$('#appointment_form_withUser').prop("disabled", false);
    });
});