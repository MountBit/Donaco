<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Notification extends Component
{
    public function render()
    {
        $notification = session('success') ?? session('error');
        // Remover a linha que limpa a sessão
        // session()->forget(['success', 'error']); // Limpa a sessão após pegar a mensagem

        return view('components.notification', [
            'notification' => $notification
        ]);
    }
}
