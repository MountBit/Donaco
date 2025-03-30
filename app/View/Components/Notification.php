<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Notification extends Component
{
    public function render()
    {
        $notification = session('success') ?? session('error');
       
        return view('components.notification', [
            'notification' => $notification
        ]);
    }
}
