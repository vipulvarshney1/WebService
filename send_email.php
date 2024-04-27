<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form fields and sanitize them
    $name = trim($_POST["contactName"]);
    $email = filter_var($_POST["contactEmail"], FILTER_SANITIZE_EMAIL);
    $subject = trim($_POST["contactSubject"]);
    $message = trim($_POST["contactMessage"]);

    // Set up the recipient email address
    $to = "vipul6542321123456@gmail.com"; 

    // Set up the email headers
    $headers = "From: $name <$email>" . "\r\n";
    $headers .= "Reply-To: $email" . "\r\n";
    $headers .= "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // Compose the email message
    $email_message = "
    <html>
    <head>
    <title>$subject</title>
    </head>
    <body>
    <p>$message</p>
    </body>
    </html>
    ";

    // Attempt to send the email
    if (mail($to, $subject, $email_message, $headers)) {
        // If successful, return success message
        echo json_encode(array('status' => 'success', 'message' => 'Your message was sent, thank you!'));
    } else {
        // If unsuccessful, return error message
        echo json_encode(array('status' => 'error', 'message' => 'Something went wrong. Please try again.'));
    }
} else {
    // If not a POST request, return error
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request.'));
}
?>
