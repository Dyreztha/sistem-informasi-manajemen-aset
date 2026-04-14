<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div>
    <!-- Page Title -->
    <h1 class="text-2xl font-bold text-white text-center mb-2">{{ __('Reset Password') }}</h1>
    <p class="text-slate-400 text-center text-sm mb-6">{{ __('Enter your new password below') }}</p>

    <form wire:submit="resetPassword" class="space-y-5">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-2">{{ __('Email') }}</label>
            <input wire:model="email" id="email" type="email" name="email" required autofocus autocomplete="username"
                   class="w-full bg-slate-700 border-slate-600 text-white placeholder-slate-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 px-4 py-3" />
            @error('email')
                <span class="text-rose-400 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-slate-300 mb-2">{{ __('Password') }}</label>
            <input wire:model="password" id="password" type="password" name="password" required autocomplete="new-password"
                   class="w-full bg-slate-700 border-slate-600 text-white placeholder-slate-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 px-4 py-3" />
            @error('password')
                <span class="text-rose-400 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-2">{{ __('Confirm Password') }}</label>
            <input wire:model="password_confirmation" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full bg-slate-700 border-slate-600 text-white placeholder-slate-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 px-4 py-3" />
            @error('password_confirmation')
                <span class="text-rose-400 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-xl py-3 font-semibold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300">
            {{ __('Reset Password') }}
        </button>
    </form>
</div>
