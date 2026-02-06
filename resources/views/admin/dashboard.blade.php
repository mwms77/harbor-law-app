{{-- resources/views/layouts/admin.blade.php --}}
{{-- RESPONSIVE ADMIN LAYOUT --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Admin') - Harbor Law</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    
    {{-- Mobile-First Responsive Navigation --}}
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                {{-- Logo and Brand --}}
                <div class="flex items-center">
                    {{-- Mobile menu button --}}
                    <button type="button" 
                            id="mobile-menu-button"
                            class="lg:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                        <span class="sr-only">Open main menu</span>
                        {{-- Hamburger icon --}}
                        <svg class="block h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    
                    {{-- Logo --}}
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center ml-4 lg:ml-0">
                        <span class="text-lg sm:text-xl font-bold text-gray-900">Harbor Law</span>
                        <span class="ml-2 px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded hidden sm:inline-block">
                            Admin
                        </span>
                    </a>
                </div>
                
                {{-- Desktop Navigation --}}
                <div class="hidden lg:flex lg:items-center lg:space-x-8">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.users.index') }}" 
                       class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'bg-gray-100' : '' }}">
                        Users
                    </a>
                    <a href="{{ route('admin.intake.index') }}" 
                       class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.intake.*') ? 'bg-gray-100' : '' }}">
                        Intake Forms
                    </a>
                    <a href="{{ route('admin.documents.index') }}" 
                       class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.documents.*') ? 'bg-gray-100' : '' }}">
                        Documents
                    </a>
                </div>
                
                {{-- User Menu (Desktop & Mobile) --}}
                <div class="flex items-center">
                    <div class="relative">
                        <button type="button" 
                                id="user-menu-button"
                                class="flex items-center max-w-xs rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span class="sr-only">Open user menu</span>
                            <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center">
                                <span class="text-sm font-medium text-white">
                                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                                </span>
                            </div>
                            <span class="hidden sm:block ml-3 text-sm font-medium text-gray-700">
                                {{ Auth::user()->name ?? 'Admin' }}
                            </span>
                            <svg class="hidden sm:block ml-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        
                        {{-- User dropdown --}}
                        <div id="user-menu" 
                             class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 z-50">
                            <div class="py-1">
                                <a href="{{ route('admin.profile') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Your Profile
                                </a>
                                <a href="{{ route('admin.settings') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Settings
                                </a>
                            </div>
                            <div class="py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Mobile menu --}}
        <div id="mobile-menu" class="hidden lg:hidden border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('admin.dashboard') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 {{ request()->routeIs('admin.users.*') ? 'bg-gray-100' : '' }}">
                    Users
                </a>
                <a href="{{ route('admin.intake.index') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 {{ request()->routeIs('admin.intake.*') ? 'bg-gray-100' : '' }}">
                    Intake Forms
                </a>
                <a href="{{ route('admin.documents.index') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 {{ request()->routeIs('admin.documents.*') ? 'bg-gray-100' : '' }}">
                    Documents
                </a>
            </div>
        </div>
    </nav>
    
    {{-- Success/Error Messages --}}
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="rounded-md bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    @if(session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="rounded-md bg-red-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>
    
    {{-- Footer --}}
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <p class="text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Harbor Law. All rights reserved.
            </p>
        </div>
    </footer>
    
    {{-- Mobile Menu Toggle Script --}}
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
        
        // User menu toggle
        document.getElementById('user-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        });
        
        // Close user menu when clicking outside
        document.addEventListener('click', function(event) {
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenu = document.getElementById('user-menu');
            
            if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
