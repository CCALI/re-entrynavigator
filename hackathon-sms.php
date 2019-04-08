<?php
/***
Messaging integration
Tobias Nteireho
The Center for Computer Assisted Legal Instruction
***/

$config = parse_ini_file('/vol/data/sites/private/config.ini');

// Update the path below to your autoload.php,
// see https://getcomposer.org/doc/01-basic-usage.md
require_once $config['lib_dir'] . '/twilio-php-master/Twilio/autoload.php';
require_once $config['lib_dir'] . '/PHPMailer-master/src/Exception.php';
require_once $config['lib_dir'] . '/PHPMailer-master/src/PHPMailer.php';
require_once $config['lib_dir'] . '/PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '/ics.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


use Twilio\Rest\Client;

// Find your Account Sid and Auth Token at twilio.com/console
// DANGER! This is insecure. See http://twil.io/secure



$sid    = $config['twilio_sid']; //"ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
$token  = $config['twilio_token']; //"your_auth_token";
$twilio_from  = $config['twilio_from']; //"your_auth_token";
$twilio = new Client($sid, $token);

/*
PO Frequency MC
Daily
Weekly
Every Other Week

Date next visit DA
Phone TE
Email TE
*/


//Load data from answerset
$AnswerKey = isset($_POST['AnswerKey']) ? $_POST['AnswerKey'] : null ;

// Get user names, submit date
//$results = $xslt->transformToDoc($xmlobject);
//$s = simplexml_load_string($AnswerKey);
$doc = new DOMDocument();
$doc->loadXML($AnswerKey);
$xpath = new DOMXPath($doc);
$firstname = $xpath->query("//Answer[@name='Client first name TE']/TextValue")->item(0)->nodeValue;

$lastname = $xpath->query("//Answer[@name='Client last name TE']/TextValue")->item(0)->nodeValue;

$client_phone = $xpath->query("//Answer[@name='Phone TE']/TextValue")->item(0)->nodeValue;
//print(strlen($client_phone));
$client_email= $xpath->query("//Answer[@name='Email TE']/TextValue")->item(0)->nodeValue;
$next_visit_date = $xpath->query("//Answer[@name='Date next visit DA']/DateValue")->item(0)->nodeValue;
$visit_frequency = $xpath->query("//Answer[@name='PO Frequency MC']/MCValue/SelValue")->item(0)->nodeValue;



//Calculate date
date_default_timezone_set('America/New_York');
$next_visit = new DateTime(str_replace ('/', '-', $next_visit_date));
$today = new DateTime(date('Y-m-d H:i:s'));
$interval =  $today->diff($next_visit);
//echo $today->format('Y-m-d') . ' ' . $next_visit_date . ' ' .  $next_visit->format('Y-m-d') . ' ' . $interval->h;
$meeting_day = $next_visit->format("D, M d Y");

/*
if ($interval->d == 1){
  $meeting_day = "tomorrow";
} else if ($interval->d == 0){
  $meeting_day = "today";
}
*/

//Send text Message
$sms_body = $firstname .
  ', this is a reminder that you have a meeting with ' .
  'your parole officer '.  $meeting_day . '.';

if (strlen($client_phone)){// && (($interval->d <= 1))){
  $message = $twilio->messages
                    ->create("+1" . $client_phone,
                             array(
                                 "body" => $sms_body,
                                 "from" => $twilio_from
                             )
                    );
}


//Make calendar

$mail_body = $firstname .
  ', this is a reminder that you have a meeting with ' .
  'your parole officer '.  $meeting_day . '.';
//Send email
if (strlen($client_email)){
  // Instantiation and passing `true` enables exceptions
  $mail = new PHPMailer(true);

  try {
      //Server settings
      $mail->SMTPDebug = 2;                                       // Enable verbose debug output
      $mail->isSMTP();                                            // Set mailer to use SMTP
      $mail->Host       = 'localhost';  // Specify main and backup SMTP servers
      //$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
      //$mail->Username   = $config['smtp_user'];                     // SMTP username
      //$mail->Password   = $config['smtp_pass'];                               // SMTP password
      //$mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
      $mail->Port       = 25;                                    // TCP port to connect to

      //Recipients
      $mail->setFrom('no-reply@a2j.org', 'a2jauthor hackathon');
      $mail->addAddress('tobias@cali.org', 'Tobias');     // Add a recipient

      // Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments

      // Content
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Parole Officer Meeting Reminder';
      $mail->Body    = $mail_body;
      $mail->AltBody = $mail_body;

      //$mail->send();
      echo 'Message has been sent';
  } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }

$to      = $client_email;
$subject = 'Parole Meeting Reminder';
$message = $mail_body;
$headers = 'From: no-reply@a2j.org' . "\r\n" .
    'Reply-To: no-reply@a2j.org' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);

}

echo "<br>Thank you for using Re-entry Navigator!";
//print($message->sid);
