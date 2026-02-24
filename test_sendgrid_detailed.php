<?php
require_once __DIR__ . '/includes/db_functions.php';
require_once __DIR__ . '/includes/email.php';

$email_to_test = 'josemwaks04@gmail.com'; // Try sending to this directly

echo "Testing SendGrid API directly...\n";
$api_key = getenv('SENDGRID_API_KEY');
$from_email = getenv('SENDGRID_FROM_EMAIL');

echo "SENDGRID_API_KEY length: " . strlen($api_key) . "\n";
echo "SENDGRID_FROM_EMAIL: " . $from_email . "\n";

if (!$api_key || !$from_email) {
    echo "Error: Missing environment variables.\n";
    exit;
}


echo "Attempting to send a test email to $email_to_test\n\n";

// Re-write the cURL request locally so we can echo the exact HTTP response body
$url = 'https://api.sendgrid.com/v3/mail/send';
$data = [
    'personalizations' => [
        [
            'to' => [['email' => $email_to_test]],
            'subject' => 'SokoSafi SendGrid Diagnostic Test'
        ]
    ],
    'from' => ['email' => $from_email],
    'content' => [['type' => 'text/plain', 'value' => 'This is a test from the diagnostic script.']]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $api_key,
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $http_code\n";
if ($curl_error) {
    echo "cURL Error: $curl_error\n";
}
echo "Response Body: \n$response\n";

if ($http_code >= 200 && $http_code < 300) {
    echo "\n=> SendGrid accepted the request! The email should arrive shortly.\n";
}
else {
    echo "\n=> SendGrid REJECTED the request. Please read the error above.\n";
}
?>
