<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $data;

    public function __construct($data)
    {
        $this->data = $data->toArray();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->data['locale'] = app()->getLocale();
        $this->data['user'] = User::auth();
        $this->data['url'] = route('admin.orders.view', ['id' => $this->data['order_id']]);

        return $this->subject(__('app.order subject'))->view('site.notifications.new_order', $this->data);
    }
}
