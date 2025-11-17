<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    protected $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true); // true enables exceptions
        $this->configure();
    }

    /**
     * I-configure ang PHPMailer gamit ang .env credentials.
     */
    protected function configure()
    {
        // Tanging para sa debugging. Huwag gamitin sa production.
        // $this->mailer->SMTPDebug = 2; 

        $this->mailer->isSMTP();
        $this->mailer->Host       = config('mail.mailers.smtp.host');
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = config('mail.mailers.smtp.username');
        $this->mailer->Password   = config('mail.mailers.smtp.password');
        $this->mailer->SMTPSecure = config('mail.mailers.smtp.encryption');
        $this->mailer->Port       = config('mail.mailers.smtp.port');

        // Set ang "From" address
        $this->mailer->setFrom(config('mail.from.address'), config('mail.from.name'));
    }

    /**
     * Ang pangunahing function para magpadala ng email.
     *
     * @param string $toEmail Ang email address ng tatanggap.
     * @param string $toName Ang pangalan ng tatanggap.
     * @param string $subject Ang subject ng email.
     * @param string $htmlBody Ang buong HTML content ng email.
     * @return bool
     */
    public function sendEmail(string $toEmail, string $toName, string $subject, string $htmlBody): bool
    {
        try {
            // Recipients
            $this->mailer->addAddress($toEmail, $toName);

            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $htmlBody;
            // Opsyonal: $this->mailer->AltBody = 'Ito ay plain-text body...';

            $this->mailer->send();
            return true;

        } catch (Exception $e) {
            // Log ang error para sa debugging
            // error_log("Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}");
            return false;
        }
    }
}