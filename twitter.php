<?php
// https://github.com/meBerkay
// Twitter API kütüphanesini dahil et
// https://github.com/abraham/twitteroauth

require_once "twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

// Twitter API kimlik bilgilerini ayarla
$consumerKey = "your_consumer_key";
$consumerSecret = "your_consumer_secret";
$accessToken = "your_access_token";
$accessTokenSecret = "your_access_token_secret";

// Twitter API'ye bağlan
$connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

// Son 10 trend konuyu al
$trends = $connection->get("trends/place", ["id" => 23424969]);
$trendTopics = array();
foreach ($trends[0]->trends as $trend) {
    array_push($trendTopics, $trend->name);
}

// Trend konuları ile e-posta içeriğini derle
$emailContent = "Here are the latest 10 trending topics on Twitter:\n\n";
foreach ($trendTopics as $topic) {
    $emailContent .= "- $topic\n";
}

// SMTP kullanarak e-posta göndermek için PHPMailer'ı kullan
// https://github.com/PHPMailer/PHPMailer
require 'vendor/autoload.php';

$mail = new PHPMailer;

$mail->isSMTP();
$mail->Host = 'smtp.example.com';
$mail->SMTPAuth = true;
$mail->Username = 'your_username';
$mail->Password = 'your_password';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('sender@example.com', 'Twitter Trending Topics');
$mail->addAddress('recipient@example.com');

$mail->Subject = 'Twitter Trending Topics';
$mail->Body = $emailContent;

if (!$mail->send()) {
    echo "Email could not be sent.\n";
    echo "Error: " . $mail->ErrorInfo;
} else {
    echo "Email sent successfully!";
}

?>
