<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require './../vendor/autoload.php';

class mailer {
    private $connect;
    
    function __construct() {
        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.mailtrap.io';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = '637ed9e41ce265';                     // SMTP username
        $mail->Password   = 'a81489b397504a';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 2525;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        $mail->CharSet    = "UTF-8";
        $this->connect    = $mail;
    }
    
    private function validatedEmail(string $email) :bool {
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }
    
    public function sendMail($email, $fileName) {
        if(!$this->validatedEmail($email)) {
            throw new Exception("Posłaniec zabłądził z twoją przesyłką. Twój adres nie leży w tej krainie :{$email}", 400);
        }
        try {
            //Recipients
            $this->connect->setFrom('poslaniec@kdz.com', 'Posłaniec');
            $this->connect->addAddress($email, 'Gracz_1');

            // Attachments
            $this->connect->addAttachment($fileName, 'Skrzynia.pdf'); 

            // Content
            $this->connect->isHTML(true);
            $this->connect->Subject = 'Twoje łupy';
            $this->connect->Body    = 'Otwóż skrzynie aby sprawdzić swoje łupy.';
            $this->connect->AltBody = 'Otwóż skrzynie aby sprawdzić swoje łupy.';

            $this->connect->send();
            return 'Posłaniec wyruszył w droge z twoimi łupami.';
        } catch (Exception $e) {
            throw new Exception("Posłaniec nie mógł wyruszyć. Jego wymówka: {$this->connect->ErrorInfo}", 500);
        }
    }
}
