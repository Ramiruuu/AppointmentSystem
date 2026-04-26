@extends('layouts.guest')
@section('title', 'Login')

@section('content')
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Left Side - Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12 lg:px-12 bg-white order-2 lg:order-1">
            <div class="max-w-md w-full">
                <!-- Logo -->
                <div class="mb-10">
                    <div class="flex items-center gap-2 justify-center lg:justify-start">
                        <div
                            class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-500 to-purple-700 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M19 3h-1V1h-2v2H8V1H6v2H5C3.9 3 3 3.9 3 5v16c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 18H5V9h14v12zM7 11h5v5H7z" />
                            </svg>
                        </div>
                        <span class="font-display font-bold text-2xl text-gray-900 tracking-tight">BookEase</span>
                    </div>
                </div>

                <div class="text-center lg:text-left">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-3 tracking-tight">Welcome back</h2>
                    <p class="text-gray-500 mb-8 leading-relaxed">Sign in to manage your appointments</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition outline-none @error('email') border-red-500 @enderror"
                            placeholder="client@example.com">
                        @error('email')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex justify-between mb-2">
                            <label for="password" class="text-sm font-medium text-gray-700">Password</label>
                            <a href="{{ route('password.request') }}"
                                class="text-sm text-violet-600 hover:text-violet-700 transition">Forgot password?</a>
                        </div>
                        <input id="password" type="password" name="password" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition outline-none"
                            placeholder="Enter your password">
                    </div>

                    <button type="submit"
                        class="w-full bg-gray-900 text-white py-3 rounded-xl font-semibold hover:bg-gray-800 transition mt-8">
                        Sign in
                    </button>
                </form>

                <p class="text-center text-sm text-gray-600 mt-8">
                    Don't have an account?
                    <a href="{{ route('register') }}"
                        class="text-violet-600 font-semibold hover:text-violet-700 transition">Create account</a>
                </p>
            </div>
        </div>

        <!-- Right Side - Hero -->
        <div
            class="w-full lg:w-1/2 bg-gradient-to-br from-violet-600 to-purple-800 p-8 lg:p-12 flex flex-col justify-between order-1 lg:order-2 min-h-[300px] lg:min-h-screen">
            <div class="text-center lg:text-left">
                <p class="text-white/60 text-sm font-medium tracking-wide mb-8">BOOKEASE</p>
                <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4 leading-tight">Simple Online Appointment System</h2>
                <p class="text-white/70 text-base leading-relaxed max-w-md mx-auto lg:mx-0">
                    Streamline your scheduling, reduce no-shows, and focus on what matters most — your clients.
                </p>
            </div>

            <!-- AESTHETIC: Trusted by Professionals section - redesigned -->
            <div class="mt-16 lg:mt-20">
                <!-- Divider line -->
                <div class="flex items-center justify-center lg:justify-start gap-3 mb-6">
                    <div class="h-px bg-white/20 w-8"></div>
                    <p class="text-white/50 text-[11px] tracking-[0.2em] uppercase font-semibold">Trusted by Professionals</p>
                    <div class="h-px bg-white/20 flex-1 hidden lg:block"></div>
                </div>
                
                <!-- Professional badges with modern design -->
                <div class="flex flex-wrap justify-center lg:justify-start gap-3">
                    <span class="px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm text-white/90 text-sm font-medium border border-white/20 hover:bg-white/20 transition-all duration-300 cursor-default">Clinics</span>
                    <span class="px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm text-white/90 text-sm font-medium border border-white/20 hover:bg-white/20 transition-all duration-300 cursor-default">Salons</span>
                    <span class="px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm text-white/90 text-sm font-medium border border-white/20 hover:bg-white/20 transition-all duration-300 cursor-default">Consultants</span>
                    <span class="px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm text-white/90 text-sm font-medium border border-white/20 hover:bg-white/20 transition-all duration-300 cursor-default">Therapists</span>
                    <span class="px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm text-white/90 text-sm font-medium border border-white/20 hover:bg-white/20 transition-all duration-300 cursor-default">Coaches</span>
                </div>
            </div>
        </div>
    </div>
@endsection