<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

$mail = new PHPMailer(true);

try {

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;

$mail->Username = 'jraldybaco@gmail.com';
$mail->Password = 'lkubyxyqvnwdfzmk';

$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('jraldybaco@gmail.com', 'IoT Room Monitor');
$mail->addAddress('20231782@nbsc.edu.ph');

$mail->Subject = 'IoT Alert: AC Runtime Warning';

$mail->Body = 'Air conditioner has been running for more than 4 hours in Lab 1. Please check the system.';

$mail->send();

echo "Email Sent";

} catch (Exception $e) {

echo "Email Failed";
}

?>