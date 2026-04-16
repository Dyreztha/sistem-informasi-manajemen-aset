<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <!-- Page Title -->
    <h1 class="text-2xl font-bold text-white text-center mb-2">{{ __('Forgot Password') }}</h1>
    <p class="text-slate-400 text-center text-sm mb-6">
        {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </p>

    <!-- Session Status -->
    @if (session('status'))
        <div class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 rounded-xl p-4 mb-6">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="sendPasswordResetLink" class="space-y-5">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-2">{{ __('Email') }}</label>
            <input wire:model="email" id="email" type="email" name="email" required autofocus
                   class="w-full bg-slate-700 border-slate-600 text-white placeholder-slate-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 px-4 py-3" />
            @error('email')
                <span class="text-rose-400 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-xl py-3 font-semibold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300">
            {{ __('Email Password Reset Link') }}
        </button>

        <!-- Back to Login Link -->
        <p class="text-center text-slate-400 text-sm">
            {{ __('Remember your password?') }}
            <a href="{{ route('login') }}" wire:navigate class="text-blue-400 hover:text-blue-300 font-medium">
                {{ __('Sign in') }}
            </a>
        </p>
    </form>
</div>
