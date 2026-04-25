<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — BookEase</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-blue-600">BookEase</h1>
            <p class="text-gray-500 text-sm mt-1">Sign in to your account</p>
        </div>
        <div class="bg-white rounded-2xl shadow-xl p-8 space-y-5">

            @if(session('status'))
                <div class="bg-green-50 text-green-700 border border-green-200 rounded-lg px-4 py-2 text-sm">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-400 @enderror">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <label class="text-sm font-medium text-gray-700">Password</label>
                            @if(Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:underline">Forgot password?</a>
                            @endif
                        </div>
                        <input type="password" name="password" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-blue-600">
                        <label for="remember" class="text-sm text-gray-600">Remember me</label>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold text-sm hover:bg-blue-700 transition">
                        Sign In
                    </button>
                </div>
            </form>

            <p class="text-center text-sm text-gray-500">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:underline">Register</a>
            </p>
        </div>
    </div>
</body>
</html>