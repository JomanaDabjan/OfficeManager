<x-guest-layout>
    <!-- ================================================================= -->
    <!-- PRELOADER / LOADING SCREEN SECTION                                -->
    <!-- Handles the loading animation when the page first opens           -->
    <!-- ================================================================= -->
    <div id="loading-screen"
        class="fixed inset-0 bg-slate-950 z-50 flex flex-col justify-center items-center transition-opacity duration-700 @if($errors->any()) hidden @endif">
        <div class="text-center">
            <h1 class="text-white text-4xl font-extrabold tracking-wider mb-6">
                Core<span class="text-orange-500">Task</span>
            </h1>
            <div
                class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-orange-500 border-t-transparent shadow-lg shadow-orange-500/30">
            </div>
            <p class="text-gray-400 text-sm mt-4 tracking-widest uppercase font-medium">Initializing Workspace...</p>
        </div>
    </div>

    <!-- ================================================================= -->
    <!-- JAVASCRIPT LOGIC FOR PRELOADER CONTROL                            -->
    <!-- ================================================================= -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loader = document.getElementById('loading-screen');

            @if ($errors->any())
                if (loader) {
                    loader.style.display = 'none';
                }
            @else
                if (loader) {
                    window.addEventListener('load', function () {
                        setTimeout(() => {
                            loader.style.opacity = '0';
                            setTimeout(() => {
                                loader.style.display = 'none';
                            }, 700);
                        }, 1000);
                    });
                }
            @endif

            const loginForm = document.querySelector('form');
            if (loginForm) {
                loginForm.addEventListener('submit', function () {
                    if (loader) {
                        loader.style.display = 'none';
                    }
                });
            }
        });
    </script>

    <!-- ================================================================= -->
    <!-- LOGIN CARD CONTAINER SECTION                                      -->
    <!-- Main wrapper card for the login form elements                     -->
    <!-- ================================================================= -->
    <div
        class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden p-8 border border-orange-100 transform transition-all duration-300">

        <!-- ================================================================= -->
        <!-- BRAND HEADER SECTION                                              -->
        <!-- Displays application logo and system description                  -->
        <!-- ================================================================= -->
        <div class="text-center mb-6">
            <h2 class="text-3xl font-black text-gray-800 tracking-wide">Core<span class="text-orange-500">Task</span>
            </h2>
            <p class="text-sm text-gray-500 mt-1 font-medium">Software Development Management System</p>
        </div>

        <!-- ================================================================= -->
        <!-- INCLUDED ALERTS COMPONENTS                                        -->
        <!-- Renders flash alerts or error notifications if available          -->
        <!-- ================================================================= -->
        <x-alert-message />

        <x-auth-session-status class="mb-4 text-sm text-green-600 text-center font-medium"
            :status="session('status')" />

        <!-- ================================================================= -->
        <!-- LOGIN FORM START                                                  -->
        <!-- ================================================================= -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- =========================================================== -->
            <!-- EMAIL ADDRESS INPUT FIELD                                   -->
            <!-- =========================================================== -->
            <div class="mb-5">
                <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Corporate
                    Email</label>
                <div class="relative">
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        autocomplete="username"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm text-gray-800 placeholder-gray-400 outline-none transition bg-gray-50/50"
                        placeholder="developer@coretask.com">
                </div>
                <!-- Validation error message display for email input -->
                @error('email')
                @if (!str_contains($message, 'Too many') && !str_contains($message, 'throttle'))
                <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                @endif
                @enderror
            </div>

            <!-- =========================================================== -->
            <!-- PASSWORD INPUT FIELD                                        -->
            <!-- =========================================================== -->
            <div class="mb-6">
                <label for="password"
                    class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Password</label>
                <div class="relative">
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm text-gray-800 placeholder-gray-400 outline-none transition bg-gray-50/50"
                        placeholder="••••••••••••">
                </div>
                <!-- Validation error message display for password input -->
                @error('password')
                <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- =========================================================== -->
            <!-- REMEMBER ME CHECKBOX FIELD (NEW ADDITION)                   -->
            <!-- Allows Laravel to keep the user authenticated via cookies   -->
            <!-- =========================================================== -->
            <div class="mb-6 flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <!-- Checkbox input bound to the 'remember' request attribute -->
                    <input id="remember_me" type="checkbox" name="remember"
                        class="rounded border-gray-300 text-orange-500 shadow-sm focus:ring-orange-500 focus:ring-2 w-4 h-4">
                    <span class="ml-2 text-xs font-bold text-gray-600 uppercase tracking-wider">Remember Me</span>
                </label>
            </div>

            <!-- =========================================================== -->
            <!-- SUBMIT BUTTON                                               -->
            <!-- =========================================================== -->
            <div>
                <button type="submit"
                    class="w-full py-3.5 px-4 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-500/30 transition-all duration-200 text-center tracking-wide uppercase text-sm">
                    Sign In to System
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
