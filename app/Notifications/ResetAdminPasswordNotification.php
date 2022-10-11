<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetAdminPasswordNotification extends Notification
{
    public $token;
    private $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('admin.password.recover', ['token' => $this->token, 'email' => $this->email]);

        return (new MailMessage)
            ->subject('Востоновление пароля администратора на сайте ' . env('APP_NAME'))
            ->view('admin.notifications.password_reset', ['url' => $url]);
    }
}
