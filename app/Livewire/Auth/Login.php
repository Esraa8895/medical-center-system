<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.guest');
    }
}