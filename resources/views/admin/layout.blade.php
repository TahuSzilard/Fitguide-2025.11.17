<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FitGuide Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" />
</head>

<body class="bg-gray-100">

    <div class="flex">

        {{-- SIDEBAR --}}
        <aside class="w-64 h-screen bg-gray-900 text-white p-6 space-y-8">

            <div class="text-2xl font-bold">Admin</div>

            <nav class="space-y-3 text-gray-300">

                <a href="{{ route('admin.dashboard') }}"
                class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->is('admin/dashboard') ? 'bg-gray-700 text-white' : '' }}">
                Dashboard
                </a>

                <a href="{{ route('admin.products.index') }}"
                    class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->is('admin/products*') ? 'bg-gray-700 text-white' : '' }}">
                    Products
                </a>

                <a href="{{ route('admin.orders.index') }}"
                    class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->is('admin/orders*') ? 'bg-gray-700 text-white' : '' }}">
                    Orders
                </a>

                <a href="{{ route('admin.users.index') }}"
                    class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->is('admin/users*') ? 'bg-gray-700 text-white' : '' }}">
                Users
                </a>

                {{-- DIVIDER --}}
                <div class="pt-8 border-t border-gray-700"></div>

                {{-- SETTINGS --}}
                <a href="#"
                class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-700">
                    <i class="fa-solid fa-gear"></i>
                    Settings
                </a>

                {{-- 🔙 BACK TO SITE --}}
                <a href="{{ route('home') }}"
                class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-700 text-blue-300">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Site
                </a>
                {{-- LOGOUT --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full text-left flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-700 text-red-400">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Logout
                    </button>
                </form>

            </nav>

        </aside>

        {{-- MAIN --}}
        <main class="flex-1">
            @yield('content')
        </main>

    </div>

    @stack('scripts')
</body>
</html>
