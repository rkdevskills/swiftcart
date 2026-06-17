<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ config('app.name') }} — @yield('title', 'Shop')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

    {{-- Navbar --}}
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="text-xl font-bold text-indigo-600">
                🛍️ {{ config('app.name') }}
            </a>

            {{-- Search --}}
            <form method="GET" action="{{ route('products.index') }}" class="hidden md:flex items-center w-1/3">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search products..."
                    class="w-full border border-gray-300 rounded-l-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                />
                <button class="bg-indigo-600 text-white px-4 py-2 rounded-r-lg text-sm hover:bg-indigo-700">
                    Search
                </button>
            </form>

            {{-- Nav Links --}}
            {{-- Nav Links --}}
            <div class="flex items-center gap-4 text-sm">
                @auth
                    <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-indigo-600">
                        🛒 Cart
                        @php $cartCount = auth()->user()->cartItems()->sum('quantity'); @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-3 bg-indigo-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-indigo-600">📦 My Orders</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-indigo-600">⚙️ Admin</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-gray-600 hover:text-red-500">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600">Login</a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Page Content --}}
    <main class="flex-1 max-w-7xl mx-auto px-4 py-8 w-full">
        @if(session('success'))
            <div x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 3000)"
                    class="mb-4 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 3000)"
                    class="mb-4 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t mt-12 py-6 text-center text-sm text-gray-400">
        © {{ date('Y') }} {{ config('app.name') }}. Built with Laravel.
    </footer>
@stack('scripts')
</body>
</html>