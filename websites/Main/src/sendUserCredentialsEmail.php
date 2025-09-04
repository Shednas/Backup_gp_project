<?php
// sendUserCredentialsEmail.php
// Uses SendGrid API via PHP curl, no Composer or extra libraries required

function sendUserCredentialsEmail($to, $username, $password) {
    // --- CONFIG ---
    // Enter your own API key from SENDGRID
    $SENDGRID_API_KEY = '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';
    $FROM_EMAIL = 'sandesh.2024152@nami.edu.np';
    $FROM_NAME = 'University Admin';

    $subject = 'Welcome to the University Portal - Your Account Credentials';
    $body = "Dear User,\n\nWelcome to the University Management Portal!\n\nYour account has been successfully created. Please find your login credentials below:\n\nUsername: $username\nPassword: $password\n\nFor your security, we recommend changing your password after your first login.\n\nIf you have any questions or need assistance, feel free to contact the university administration.\n\nBest regards,\nUniversity Admin Team";

    $data = [
        'personalizations' => [[
            'to' => [['email' => $to]],
            'subject' => $subject
        ]],
        'from' => ['email' => $FROM_EMAIL, 'name' => $FROM_NAME],
        'content' => [[
            'type' => 'text/plain',
            'value' => $body
        ]]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.sendgrid.com/v3/mail/send');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $SENDGRID_API_KEY,
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($http_code >= 400) {
        error_log('SendGrid email failed: ' . $response);
    }
    curl_close($ch);
}

