<?php
// MOCK credentials
$botToken = '123456:ABC-DEF1234567890'; // Replace with a mock token
$chatId = '@mockgroup'; // Replace with a mock chat ID or username

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Receive and sanitize form data
    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : 'Not Provided';
    $phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : 'Not Provided';
    $telegramHandle = isset($_POST['telegram']) ? htmlspecialchars($_POST['telegram']) : 'Not Provided';

    // 2. Format the message
    $message = "New Design Request:\n\n";
    $message .= "Full Name: " . $name . "\n";
    $message .= "Phone Number: " . $phone . "\n";
    $message .= "Telegram: " . $telegramHandle;

    // 3. Use cURL to send the message to the Telegram API
    $telegramApiUrl = 'https://api.telegram.org/bot' . $botToken . '/sendMessage';

    $postData = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'HTML' // Optional: for formatting like bold, italic, etc.
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $telegramApiUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // In a real scenario, you might want to remove or handle SSL verification carefully.
    // For this mock setup, we'll proceed without strict verification.
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // 4. Redirect back to the index page with a status
    if ($httpcode == 200 && $response) {
        // Success
        header('Location: index.html?status=success');
    } else {
        // Failure
        // In a real app, you'd log the error: error_log("Telegram API error: " . $response);
        header('Location: index.html?status=error');
    }
    exit;
} else {
    // If not a POST request, redirect to the form
    header('Location: index.html');
    exit;
}
?>