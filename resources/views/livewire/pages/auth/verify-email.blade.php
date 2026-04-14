<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <!-- Page Title -->
    <h1 class="text-2xl font-bold text-white text-center mb-2">{{ __('Verify Email') }}</h1>
    <p class="text-slate-400 text-center text-sm mb-6">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 rounded-xl p-4 mb-6">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="space-y-4">
        <!-- Resend Button -->
        <button wire:click="sendVerification" type="button" class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-xl py-3 font-semibold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300">
            {{ __('Resend Verification Email') }}
        </button>

        <!-- Logout Link -->
        <p class="text-center">
            <button wire:click="logout" type="button" class="text-blue-400 hover:text-blue-300 text-sm font-medium transition-colors duration-200">
                {{ __('Log Out') }}
            </button>
        </p>
    </div>
</div>
