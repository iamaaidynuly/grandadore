<?php

namespace App\Traits;

use App\Services\SmsSender\SmsSender;
use Exception;

trait MustVerifyPhone
{
    /**
     * Determine if the user has verified their phone number.
     *
     * @return bool
     */
    public function hasVerifiedPhone()
    {
        return (bool)$this->sms_verification;
    }

    /**
     * Mark the given user's phone as verified.
     *
     * @return bool
     */
    public function markPhoneAsVerified()
    {
        return $this->forceFill([
            'sms_verification' => true,
        ])->save();
    }

    /**
     * Send the phone verification notification.
     *
     * @return void
     */
    public function sendPhoneVerificationNotification()
    {
        notify('Код для подтверждения отправлен на указанный номер');
    }

    public function sendToVerify()
    {
        $code = rand(10000, 99999);

        $this->forceFill([
            'sms_verification_code' => $code
        ])->save();

        $sender = new SmsSender();

        $message = 'Ваш код подтверждения на сайте ' . $code;

        try{
            return $sender->send($this->phone, $message);
        } catch (Exception $e) {
           return false;
        }
    }
}
