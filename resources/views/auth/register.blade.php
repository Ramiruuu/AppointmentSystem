@extends('layouts.guest')
@section('title', 'Register')

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
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-3 tracking-tight">Create an account</h2>
                    <p class="text-gray-500 mb-8 leading-relaxed">Join BookEase and start scheduling</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition outline-none @error('name') border-red-500 @enderror"
                            placeholder="Remar Gonzaga Oclarit">
                        @error('name')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition outline-none @error('email') border-red-500 @enderror"
                            placeholder="client@example.com">
                        @error('email')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone number</label>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition outline-none @error('phone') border-red-500 @enderror"
                            placeholder="+63 912 345 6789">
                        @error('phone')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input id="password" type="password" name="password" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition outline-none @error('password') border-red-500 @enderror"
                            placeholder="Create a password">
                        @error('password')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm
                            password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition outline-none"
                            placeholder="Confirm your password">
                    </div>

                    <button type="submit"
                        class="w-full bg-gray-900 text-white py-3 rounded-xl font-semibold hover:bg-gray-800 transition mt-6">
                        Create account
                    </button>
                </form>

                <p class="text-center text-sm text-gray-600 mt-8">
                    Already have an account?
                    <a href="{{ route('login') }}"
                        class="text-violet-600 font-semibold hover:text-violet-700 transition">Sign in</a>
                </p>
            </div>
        </div>

        <!-- Right Side - Hero -->
        <div
            class="w-full lg:w-1/2 bg-gradient-to-br from-violet-600 to-purple-800 p-8 lg:p-12 flex flex-col justify-between order-1 lg:order-2 min-h-[250px] lg:min-h-screen">
            <div class="text-center lg:text-left">
                <p class="text-white/60 text-sm font-medium tracking-wide mb-8">GET STARTED</p>
                <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4 leading-tight">Join thousands of satisfied users
                </h2>
                <p class="text-white/70 text-base leading-relaxed max-w-md mx-auto lg:mx-0">
                    Create your account today and experience hassle-free appointment management.
                </p>
            </div>

            <div class="text-center lg:text-left mt-8 lg:mt-0">
                <p class="text-white/40 text-xs tracking-wide mb-4">PERFECT FOR</p>
                <div class="flex flex-wrap justify-center lg:justify-start gap-6 opacity-50">
                    <span class="text-white text-xs font-medium">Small Business</span>
                    <span class="text-white text-xs font-medium">Freelancers</span>
                    <span class="text-white text-xs font-medium">Professionals</span>
                    <span class="text-white text-xs font-medium">Teams</span>
                </div>
            </div>
        </div>
    </div>
@endsection