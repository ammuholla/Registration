<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthcare";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $doctor = $_POST['doctor'];
    $symptoms = isset($_POST['symptoms']) ? implode(", ", $_POST['symptoms']) : 'None';
    $message = $_POST['message'];
    $follow_up = isset($_POST['follow_up']) ? 'Yes' : 'No';

    // Insert data into the database
    $sql = "INSERT INTO appointments (name, email, phone, gender, appointment_date, appointment_time, doctor, symptoms, message, follow_up)
            VALUES ('$name', '$email', '$phone', '$gender', '$date', '$time', '$doctor', '$symptoms', '$message', '$follow_up')";

    if ($conn->query($sql) === TRUE) {
        // Store data in the session for display
        $_SESSION['appointment_details'] = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'gender' => $gender,
            'date' => $date,
            'time' => $time,
            'doctor' => $doctor,
            'symptoms' => $symptoms,
            'message' => $message,
            'follow_up' => $follow_up,
        ];

        // Redirect to the same file to display the data
        header("Location: submit_and_display_appointment.php?success=true");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Display the appointment details if available
if (isset($_GET['success']) && isset($_SESSION['appointment_details'])) {
    $appointment = $_SESSION['appointment_details'];
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Appointment Details</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f9;
                margin: 0;
                padding: 20px;
            }
            .container {
                max-width: 600px;
                margin: auto;
                background: #fff;
                padding: 20px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
                margin-top: 50px;
            }
            h2 {
                text-align: center;
            }
            .details {
                margin-top: 20px;
            }
            .details p {
                margin: 10px 0;
                line-height: 1.6;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Appointment Details</h2>
            <div class="details">
                <p><strong>Name:</strong> <?= htmlspecialchars($appointment['name']); ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($appointment['email']); ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($appointment['phone']); ?></p>
                <p><strong>Gender:</strong> <?= htmlspecialchars($appointment['gender']); ?></p>
                <p><strong>Appointment Date:</strong> <?= htmlspecialchars($appointment['date']); ?></p>
                <p><strong>Appointment Time:</strong> <?= htmlspecialchars($appointment['time']); ?></p>
                <p><strong>Doctor:</strong> <?= htmlspecialchars($appointment['doctor']); ?></p>
                <p><strong>Symptoms:</strong> <?= htmlspecialchars($appointment['symptoms']); ?></p>
                <p><strong>Message:</strong> <?= htmlspecialchars($appointment['message']); ?></p>
                <p><strong>Follow-up Appointment:</strong> <?= htmlspecialchars($appointment['follow_up']); ?></p>
            </div>
        </div>
    </body>
    </html>
    <?php
    // Clear session data after displaying
    unset($_SESSION['appointment_details']);
    exit();
}

$conn->close();
?>
