{{-- ===== Offer Banner ===== --}}
@if(config('site.offer_banner'))
<div class="bg-green-600 text-white py-2 text-center text-sm">
    🔥🔥 Don't miss out on our amazing Cyber Monday Deals!
    <a href="https://10captcha.com/lp/cybermonday" class="ml-2 font-semibold underline hover:no-underline">
        Claim Bonus Now!
    </a>
</div>
@endif

{{-- ===== Main Navbar ===== --}}
<nav class="bg-white border-b border-gray-200" id="header-10cap" x-data="{ open: false }"
     x-init="@if($errors->any()) $store.auth.open('{{ old('_tab', 'login') }}') @endif">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex-shrink-0">
                <img src="{{ asset('assets/img/logo.png') }}" class="h-12 w-auto" alt="10captcha">
            </a>

            {{-- Desktop Right Side --}}
            <div class="hidden md:flex items-center gap-4">
                @auth
                    @php
                        $ticketCount = 0;
                    @endphp

                    {{-- Balance --}}
                    <a href="{{ url('/topup') }}" class="text-sm text-gray-700 hover:text-green-600 font-medium transition">
                        $<span id="user-balance">{{ number_format(auth()->user()->balance_5d / 100000, 2) }}</span>
                    </a>

                    <span class="text-gray-300">|</span>

                    {{-- User Dropdown --}}
                    <div class="relative" x-data="{ userOpen: false }">
                        <button @click="userOpen = !userOpen"
                                class="flex items-center gap-1 text-sm text-gray-700 hover:text-green-600 font-medium transition focus:outline-none">
                            <i class="las la-user text-lg"></i>
                            {{ auth()->user()->fname ?? auth()->user()->name }}
                            @if($ticketCount)
                                <span class="text-red-500 text-xs ml-0.5">&#9679;</span>
                            @endif
                            <i class="las la-angle-down text-xs ml-0.5"></i>
                        </button>

                        <div x-show="userOpen"
                             x-cloak
                             @click.outside="userOpen = false"
                             class="absolute right-0 mt-2 w-52 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50">
                            <a href="{{ url('/dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Dashboard</a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profile</a>
                            <a href="{{ url('/topup') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Add Funds</a>
                            <a href="{{ url('/payments') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Payments History</a>
                            <hr class="my-1 border-gray-100">
                            <a href="{{ url('/api') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">API KEY</a>
                            <a href="{{ url('/api-docs') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">API Documentation</a>
                            <a href="{{ url('/reports') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Reports</a>
                            <a href="{{ url('/tickets') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                Tickets
                                @if($ticketCount)
                                    <span class="ml-1 bg-yellow-400 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $ticketCount }}</span>
                                @endif
                            </a>
                            <hr class="my-1 border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="las la-sign-out-alt mr-1"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <button @click="$store.auth.open('login')" class="text-sm text-gray-700 hover:text-green-600 font-medium transition uppercase tracking-wide">Login</button>
                    <button @click="$store.auth.open('register')" class="px-4 py-2 border border-green-600 text-green-700 text-sm font-semibold rounded hover:bg-green-600 hover:text-white transition uppercase tracking-wide">
                        Sign Up
                    </button>
                @endauth
            </div>

            {{-- Mobile Hamburger --}}
            <button @click="open = !open" class="md:hidden text-gray-700 hover:text-green-600 focus:outline-none">
                <i class="las text-2xl" :class="open ? 'la-times' : 'la-bars'"></i>
            </button>

        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="open" x-cloak class="md:hidden border-t border-gray-100 bg-white px-4 py-3 space-y-1">
        <a href="{{ url('/') }}" class="block text-sm text-gray-700 hover:text-green-600 py-2">Home</a>
        <a href="#pricing" class="block text-sm text-gray-700 hover:text-green-600 py-2">Pricing</a>
        <a href="{{ url('/api-docs') }}" class="block text-sm text-gray-700 hover:text-green-600 py-2">API</a>
        <a href="#faq" class="block text-sm text-gray-700 hover:text-green-600 py-2">FAQ</a>
        <hr class="my-2 border-gray-100">
        @auth
            <a href="{{ url('/dashboard') }}" class="block text-sm text-gray-700 hover:text-green-600 py-2">Dashboard</a>
            <a href="{{ route('profile.edit') }}" class="block text-sm text-gray-700 hover:text-green-600 py-2">Profile</a>
            <a href="{{ url('/topup') }}" class="block text-sm text-gray-700 hover:text-green-600 py-2">Add Funds</a>
            <a href="{{ url('/tickets') }}" class="block text-sm text-gray-700 hover:text-green-600 py-2">Tickets</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left text-sm text-gray-700 hover:text-green-600 py-2">
                    Logout
                </button>
            </form>
        @else
            <button @click="open = false; $store.auth.open('login')" class="block w-full text-left text-sm text-gray-700 hover:text-green-600 py-2">Login</button>
            <button @click="open = false; $store.auth.open('register')" class="block w-full text-center mt-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
                Sign Up
            </button>
        @endauth
    </div>
</nav>

{{-- ===== Verification Warning ===== --}}
@auth
    @if(auth()->user()->status == 1 && !request()->is('verify*'))
    <div class="bg-yellow-50 border-b border-yellow-200 text-yellow-800 text-sm text-center py-2 px-4">
        ⚠️ Your account is not verified yet. Please check your email.
        <a href="{{ url('/verify') }}" class="font-semibold underline ml-1">Verify Now</a>
    </div>
    @endif
@endauth

{{-- ===== Auth Modal ===== --}}
@guest
<div x-data x-show="$store.auth.modal"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center px-4"
     @keydown.escape.window="$store.auth.close()">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50" @click="$store.auth.close()"></div>

    {{-- Modal Box --}}
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-md z-10 overflow-hidden">

        {{-- Close Button --}}
        <button @click="$store.auth.close()"
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl leading-none focus:outline-none">
            &times;
        </button>

        {{-- Tabs --}}
        <div class="flex border-b border-gray-200">
            <button @click="$store.auth.tab = 'register'"
                    :class="$store.auth.tab === 'register' ? 'border-b-2 border-green-600 text-green-600' : 'text-gray-500 hover:text-gray-700'"
                    class="flex-1 py-3 text-sm font-medium focus:outline-none transition">
                Register
            </button>
            <button @click="$store.auth.tab = 'login'"
                    :class="$store.auth.tab === 'login' ? 'border-b-2 border-green-600 text-green-600' : 'text-gray-500 hover:text-gray-700'"
                    class="flex-1 py-3 text-sm font-medium focus:outline-none transition">
                Login
            </button>
        </div>

        {{-- ── Register Form ── --}}
        <div x-show="$store.auth.tab === 'register'" class="px-8 py-6">
            <h2 class="text-2xl font-bold text-gray-900 text-center mb-1">User Register</h2>
            <p class="text-gray-500 text-center text-sm mb-5">create your account it takes only a few moments</p>

            @if($errors->any() && old('_tab') === 'register')
                <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <input type="hidden" name="_tab" value="register">

                {{-- Name --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="modal_name">Full Name</label>
                    <input id="modal_name" name="name" type="text" value="{{ old('name') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="John Doe" required autofocus>
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="modal_reg_email">Email address</label>
                    <input id="modal_reg_email" name="email" type="email" value="{{ old('email') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="you@example.com" required>
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="modal_reg_password">Password</label>
                    <input id="modal_reg_password" name="password" type="password"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="••••••••" required>
                </div>

                {{-- Confirm Password --}}
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="modal_reg_confirm">Confirm Password</label>
                    <input id="modal_reg_confirm" name="password_confirmation" type="password"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="••••••••" required>
                </div>

                <button type="submit"
                        class="w-full py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition text-sm">
                    Sign up
                </button>

                <p class="text-center text-sm text-gray-500 mt-4">
                    Already have account?
                    <button type="button" @click="$store.auth.tab = 'login'" class="text-green-600 font-medium hover:underline">Sign in</button>
                </p>
            </form>
        </div>

        {{-- ── Login Form ── --}}
        <div x-show="$store.auth.tab === 'login'" class="px-8 py-6">
            <h2 class="text-2xl font-bold text-gray-900 text-center mb-1">Sign In</h2>
            <p class="text-gray-500 text-center text-sm mb-5">sign in to your account to continue</p>

            @if($errors->any() && old('_tab') === 'login')
                <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if(session('status'))
                <div class="mb-4 text-sm text-green-600 bg-green-50 border border-green-200 rounded-lg px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <input type="hidden" name="_tab" value="login">

                {{-- Email --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="modal_email">Email address</label>
                    <input id="modal_email" name="email" type="email" value="{{ old('email') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="you@example.com" required autofocus>
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="modal_password">Password</label>
                    <input id="modal_password" name="password" type="password"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="••••••••" required>
                </div>

                {{-- Remember + Forgot --}}
                <div class="flex items-center justify-between mb-5">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        Remember me
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-green-600 hover:underline">Forgot password?</a>
                    @endif
                </div>

                <button type="submit"
                        class="w-full py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition text-sm">
                    Sign in
                </button>

                <p class="text-center text-sm text-gray-500 mt-4">
                    Don't have an account?
                    <button type="button" @click="$store.auth.tab = 'register'" class="text-green-600 font-medium hover:underline">Sign up</button>
                </p>
            </form>
        </div>

    </div>
</div>
@endguest

