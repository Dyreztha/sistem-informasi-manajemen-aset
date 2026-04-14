<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Page Title -->
    <h1 class="text-2xl font-bold text-white text-center mb-2">{{ __('Create Account') }}</h1>
    <p class="text-slate-400 text-center text-sm mb-6">{{ __('Register to get started with SIMA') }}</p>

    <form wire:submit="register" class="space-y-5">
        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-slate-300 mb-2">{{ __('Name') }}</label>
            <input wire:model="name" id="name" type="text" name="name" required autofocus autocomplete="name"
                   class="w-full bg-slate-700 border-slate-600 text-white placeholder-slate-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 px-4 py-3" />
            @error('name')
                <span class="text-rose-400 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-2">{{ __('Email') }}</label>
            <input wire:model="email" id="email" type="email" name="email" required autocomplete="username"
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
            {{ __('Register') }}
        </button>

        <!-- Login Link -->
        <p class="text-center text-slate-400 text-sm">
            {{ __('Already registered?') }}
            <a href="{{ route('login') }}" wire:navigate class="text-blue-400 hover:text-blue-300 font-medium">
                {{ __('Sign in') }}
            </a>
        </p>
    </form>
</div>
