<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Your reCAPTCHA secret key
    $recaptchaSecretKey = "6LdQZgkoAAAAAKrqqBbAYThjYqjcPjVKd-rr-Fn2";

    // Verify reCAPTCHA
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $recaptchaSecretKey,
        'response' => $recaptchaResponse,
    ];

    $options = [
        'http' => [
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $recaptchaResult = json_decode($response);

    if (!$recaptchaResult->success) {
        // Handle reCAPTCHA verification failure, e.g., show an error message and exit
        echo "reCAPTCHA verification failed. Please verify that you are not a robot.";
        exit;
    }

    // Process the rest of the form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];
    $to = "haffkematt@gmail.com"; // Replace with your email address

    $subject = "New message from $name";
    $headers = "From: $email" . "\r\n" .
        "Reply-To: $email" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();

    // Send the email
    mail($to, $subject, $message, $headers);

    // Redirect to a thank-you page or display a success message
    header("Location: index.html"); // Replace with your thank-you page
} else {
    // Handle non-POST requests (optional)
    echo "This page is not accessible directly.";
}
?>
