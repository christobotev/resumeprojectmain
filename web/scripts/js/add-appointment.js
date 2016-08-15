$(document).ready(function() {
    $("#makeAppointment").on("show.bs.modal", function(e) {
        var incomingData = $(e.relatedTarget).data();

        $('#appointment_form_withUser').val(incomingData.data.userID);
        $('#MDName').html(incomingData.data.username);
        $('#appUrl').val(incomingData.url);
    });

    $('#appointmentForm').on('submit', function(e) {
        // set error field empty
        $('#updateError').html();
        var url = $('#appUrl').val();
        var withUser = $('#appointment_form_withUser').val();
        var date = $('#appointment_form_scheduled_date').val();
        var hours = $('#appointment_form_scheduled_time_hour').val();
        var minutes = $('#appointment_form_scheduled_time_minute').val();
        var note = $('#appointment_form_content').val();
        var token = $('#appointment_form__token').val();
        var symptoms = $('#appointment_form_symptoms').val();

        $.ajax({
            type: "POST",
            dataType: 'json',
            data: {
                appointment_form :{
                    'scheduled' :
                        {
                            'date' : date,
                            'time' : {'hour' : hours, 'minute' : minutes}
                        },
                    'content' : note,
                    'withUser' : withUser,
                    'symptoms' : symptoms,
                    'save' : '',
                    '_token' : token
                },
            },
            url: url,
        })
        .success(function(response){
            location.reload();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            var error = JSON.parse(jqXHR.responseText);
            if (error.message) {
                $('#appError').html(error.message);
            } else {
                $('#appError').html(textStatus);
            }
        });
        e.preventDefault();
    });
});