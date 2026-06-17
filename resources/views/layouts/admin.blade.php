<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex">

    {{-- Sidebar --}}
    <aside class="w-64 bg-white shadow-sm min-h-screen flex flex-col shrink-0">
        <div class="px-6 py-5 border-b">
            <a href="{{ route('admin.dashboard') }}" class="text-lg font-bold text-indigo-600">
                ⚙️ Admin Panel
            </a>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                📊 Dashboard
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                📁 Categories
            </a>
            <a href="{{ route('admin.products.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.products.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                📦 Products
            </a>
            <a href="{{ route('admin.orders.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                🧾 Orders
            </a>
            <a href="{{ route('admin.reviews.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.reviews.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                ⭐ Reviews
            </a>
        </nav>

        <div class="px-4 py-4 border-t space-y-1">
            <a href="{{ route('home') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">
                🛍️ Back to Shop
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-red-500 hover:bg-red-50">
                    🚪 Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Top bar --}}
        <header class="bg-white shadow-sm px-8 py-4 flex items-center justify-between">
            <h1 class="text-lg font-bold text-gray-800">@yield('title', 'Dashboard')</h1>
            <span class="text-sm text-gray-500">👋 {{ auth()->user()->name }}</span>
        </header>

        {{-- Content --}}
        <main class="flex-1 p-8">
            @if(session('success'))
                <div x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 4000)"
                    x-transition
                    class="mb-4 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="text-green-500 hover:text-green-700 ml-4">✕</button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 4000)"
                    x-transition
                    class="mb-4 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="text-red-500 hover:text-red-700 ml-4">✕</button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>