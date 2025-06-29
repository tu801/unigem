<?php

namespace App\Libraries;

use SendGrid;
use SendGrid\Mail\Mail;

class SendGridMailer
{
    protected $apiKey;
    protected $fromEmail;
    protected $fromName;

    public function __construct()
    {
        $emailConfig = config('Email');

        $this->apiKey = getenv('sendgrid.apiKey');
        $this->fromEmail = $emailConfig->fromEmail ?? getenv('sendgrid.fromEmail');
        $this->fromName = $emailConfig->fromName ?? getenv('app.site_name');
    }

    public function send($to, $subject, $htmlContent)
    {
        $email = new Mail();
        $email->setFrom($this->fromEmail, $this->fromName);
        $email->setSubject($subject);
        $email->addTo($to);
        $email->addContent('text/html', $htmlContent);

        $sendgrid = new SendGrid($this->apiKey);

        try {
            $response = $sendgrid->send($email);
            if ($response->statusCode() >= 400) {
                log_message('error', 'SendGrid Error: ' . $response->body());
                return false;
            }
            return $response->statusCode();
        } catch (\Exception $e) {
            log_message('error', 'SendGrid Error: ' . $e->getMessage());
            return false;
        }
    }
}
