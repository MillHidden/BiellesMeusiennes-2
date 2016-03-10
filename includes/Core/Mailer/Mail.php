<?php
namespace Core\Mailer;

use Core\Configure\Config;
use PHPMailer;

Class Mail {

    public function __construct()
    {
        $this->config = Config::getConfig();
        $this->mailer = new PHPMailer();
        $this->mailer->isSMTP();
        $this->mailer->Host = $this->config->smtp->Host;
        $this->mailer->SMTPAuth = $this->config->smtp->SMTPAuth;
        $this->mailer->Username = $this->config->smtp->Username;
        $this->mailer->Password = $this->config->smtp->Password;
        $this->mailer->SMTPSecure = $this->config->smtp->SMTPSecure;
        $this->mailer->Port = $this->config->smtp->Port;
        $this->mailer->setFrom($this->config->smtp->From_adress, $this->config->smtp->From_name);
        $this->mailer->CharSet = 'UTF-8';
    }

    public function send($receiver_mail, $receiver_name, $subject, $content_text, $content_html, $pjs = [])
    {
        $content_text = $this->mailer->normalizeBreaks($content_text, "\r\n");
        $this->mailer->addAddress($receiver_mail, $receiver_name);

        if (!empty($pjs)) {
            foreach ( $pjs as $pj ) {
                $this->mailer->addAttachment($pj['path'], str_replace('output','', $pj['name']));
            }
        }
        $this->mailer->isHTML(true);
        $this->mailer->Subject = $subject;
        $this->mailer->Body    = $content_html;
        $this->mailer->AltBody = $content_text;
        if(!$this->mailer->send()) {
            return $this->mailer->ErrorInfo;
        } else {
            return true;
        }
    }
}