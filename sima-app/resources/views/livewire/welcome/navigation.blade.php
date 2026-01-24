<nav class="-mx-3 flex flex-1 justify-end items-center gap-2">
    @auth
        <a
            href="{{ url('/dashboard') }}"
            class="px-5 py-2.5 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-xl font-semibold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300"
        >
            Dashboard
        </a>
    @else
        <a
            href="{{ route('login') }}"
            class="px-5 py-2.5 text-slate-300 hover:text-white transition-colors font-medium"
        >
            Log in
        </a>

        @if (Route::has('register'))
            <a
                href="{{ route('register') }}"
                class="px-5 py-2.5 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-xl font-semibold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300"
            >
                Register
            </a>
        @endif
    @endauth
</nav>
