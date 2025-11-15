@php
    use WhitePage\Facades\WhitePage;
    use WhitePage\Components\AbstractMethod;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Auth</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Підключення Tailwind (CDN лише для демо, у проектах краще ставити через npm) -->
    <script src="//cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Log in to your account</h2>
        <div
            x-data="loginComponent"
            data-url="{{ href(WhitePage::AUTH_ROOT_PREFIX, 'login') }}"
            backward-url="{{ href(WhitePage::CMS_ROOT_PREFIX, 'project') }}"
            class="space-y-6"
        >
            <!-- CSRF token for Laravel (insert server-side) -->
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                <input
                    id="email"
                    x-model="formData.email"
                    type="email"
                    required
                    class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="you@example.com"
                />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input
                    type="password"
                    id="password"
                    x-model="formData.password"
                    required
                    class="block w-full rounded-md border border-gray-300 p-2 pr-10 focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="••••••••"
                />
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input
                        id="remember"
                        x-model="formData.remember"
                        type="checkbox"
                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                    />
                    <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
                </div>

                <div class="text-sm">
                    <a href="/password/reset" class="font-medium text-indigo-600 hover:text-indigo-500">Forgot your password?</a>
                </div>
            </div>

            <div>
                <button
                    x-on:click="submitForm"
                    class="w-full flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    Sign in
                </button>
            </div>
        </div>
    </div>
@vite(vite_package_assets([
    'resources/css/app.css',
    'resources/js/app.js',
]))
</body>
</html>
