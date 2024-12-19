$(document).ready(function() {
    $('#appointmentForm').submit(function(event) {
        event.preventDefault();  // Prevent the default form submission

        // Serialize the form data to send in the AJAX request
        var formData = $(this).serialize();

        // Perform the AJAX request to submit the form data to the PHP script
        $.ajax({
            type: 'POST',
            url: 'submit_appointment.php',
            data: formData,
            success: function(response) {
                // Display the response from the PHP script
                $('#response').html(response).show();
            },
            error: function() {
                // Handle errors if the request fails
                $('#response').html('An error occurred. Please try again.').show();
            }
        });
    });
});
