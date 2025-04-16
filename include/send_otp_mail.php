<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'include/mailer/PHPMailer.php';
require 'include/mailer/SMTP.php';
require 'include/mailer/Exception.php';

function sendOtpMail($to, $name, $otp) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';          // Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'bhanuprakashofcl@gmail.com'; // 🔁 Replace with your Gmail
        $mail->Password = 'upqd ysyn qesn hhpe';    // 🔁 Replace with app password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('your-email@gmail.com', 'RealEstate PHP');
        $mail->addAddress($to, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Registration';
        $mail->Body    = "
            Hello <b>$name</b>,<br><br>
            Your OTP for registration is: <h2>$otp</h2><br>
            Do not share it with anyone.<br><br>
            Regards,<br>
            RealEstate PHP Team
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
