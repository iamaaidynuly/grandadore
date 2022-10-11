<?php

namespace App\Services\Notify;

class Notify
{
    private $next_notifications = [];
    private $this_notifications;
    private $session_key = 'toastr_notifications';
    private $flashes_got = false;

    private function getFlashes()
    {
        if ($this->flashes_got) return false;
        $this->flashes_got = true;
        $notifications = session()->get($this->session_key);
        if (!empty($notifications)) {
            $this->this_notifications = $notifications;
        } else {
            $this->this_notifications = [];
        }

        return true;
    }

    private function add($type, $message, $title, $this_page)
    {
        $notification = [
            'type' => $type,
            'message' => $message,
            'title' => $title,
        ];
        if ($this_page) {
            $this->getFlashes();
            $this->this_notifications[] = $notification;
        } else {
            $this->next_notifications[] = $notification;
            session()->flash($this->session_key, $this->next_notifications);
        }
    }

    public function success($message, $title = null, $this_page = false)
    {
        $this->add('success', $message, $title, $this_page);
    }

    public function info($message, $title = null, $this_page = false)
    {
        $this->add('info', $message, $title, $this_page);
    }

    public function warning($message, $title = null, $this_page = false)
    {
        $this->add('warning', $message, $title, $this_page);
    }

    public function error($message, $title = null, $this_page = false)
    {
        $this->add('error', $message, $title, $this_page);
    }

    public function get($key, $this_page = false)
    {
        $message = config('notify.messages.' . $key);
        if (!empty($message)) {
            $this->add($message['type'], $message['message'], $message['title'] ?? null, $this_page);

            return true;
        } else return false;
    }

    public function render()
    {
        $this->getFlashes();
        $scripts = '';
        foreach ($this->this_notifications as $notification) {
            $scripts .= 'toastr.' . $notification['type'] . '(\'' . $notification['message'] . '\'' . (empty($notification['title']) ? null : ',\'' . $notification['title'] . '\'') . ');';
        }

        return $scripts ? '<script>toastr.options.showMethod=\'show\';setTimeout(function(){' . $scripts . '}, 700);</script>' : null;
    }

    public function clear()
    {
        $this->next_notifications = [];
        $this->this_notifications = [];
        $this->flashes_got = true;
        session()->forget($this->session_key);

        return true;
    }
}
