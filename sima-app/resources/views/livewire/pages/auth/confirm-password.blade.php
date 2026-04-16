<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Page Title -->
    <h1 class="text-2xl font-bold text-white text-center mb-2">{{ __('Confirm Password') }}</h1>
    <p class="text-slate-400 text-center text-sm mb-6">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </p>

    <form wire:submit="confirmPassword" class="space-y-5">
        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-slate-300 mb-2">{{ __('Password') }}</label>
            <input wire:model="password" id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full bg-slate-700 border-slate-600 text-white placeholder-slate-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 px-4 py-3" />
            @error('password')
                <span class="text-rose-400 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-xl py-3 font-semibold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300">
            {{ __('Confirm') }}
        </button>
    </form>
</div>
