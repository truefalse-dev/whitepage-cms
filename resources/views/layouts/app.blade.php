<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Adminpanel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Підключення Tailwind (CDN лише для демо, у проектах краще ставити через npm) -->
    <script src="//cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-60 bg-gray-800 text-white flex-shrink-0 flex flex-col">
        <div class="py-5 px-4 text-center border-b border-gray-700">
            <span class="font-bold text-lg tracking-widest">Adminpanel</span>
        </div>
        <nav class="flex-1 py-6">
            <ul class="space-y-1">
                <li>
                    <a href="#" class="block px-6 py-3 bg-gray-700 rounded-l-full font-semibold text-orange-400">
                        Home
                    </a>
                </li>
                @foreach(menu() as $item)
                    <a href="{{ href(\WhitePage\Facades\WhitePage::CMS_ROOT_PREFIX, $item->name) }}" class="block px-6 py-3 hover:bg-gray-700 hover:text-orange-400 transition-all">{{ $item->label }}</a>
                @endforeach
            </ul>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        @yield('content')
    </main>
</div>
@vite(vite_package_assets([
    'resources/css/app.css',
    'resources/js/app.js',
]))
</body>
</html>
