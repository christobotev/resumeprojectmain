$(document).ready(function() {
    $("#createReminder").on("show.bs.modal", function(e) {
        var incomingData = $(e.relatedTarget).data();

        $('#appointment_user').val(incomingData.userid);
        $('#reminderURL').val(incomingData.url);
        $('#reminderForm').on('submit', function(e) {
            // set error field empty
            $('#reminderError').html();
            var url = $('#reminderURL').val();
            var date = $('#reminder_form_datetime').val();
            var md = $('#reminder_form_md').val();
            var note = $('#reminder_form_note').val();
            var token = $('#reminder_form__token').val();

            $.ajax({
                type: "POST",
                dataType: 'json',
                data: {
                    reminder_form :{
                        'datetime' : date,
                        'md' : md,
                        'note' : note,
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
                    $('#reminderError').html(error.message);
                } else {
                    $('#reminderError').html(textStatus);
                }
            });
            e.preventDefault();
        });
    });
});