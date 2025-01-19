<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('errors.403.title') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css'])
</head>
<body class="antialiased bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen flex flex-col items-center justify-center p-4">
        <div class="max-w-md w-full bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="text-center">
                    <div class="bg-red-100 dark:bg-red-900 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-lock text-3xl text-red-600 dark:text-red-300"></i>
                    </div>
                    
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('errors.403.title') }}
                    </h1>
                    
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        {{ $message ?? __('errors.403.message') }}
                    </p>

                    <div class="flex justify-center gap-4">
                        <a href="{{ url()->previous() }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700">
                            <i class="fas fa-arrow-left mr-2"></i>
                            {{ __('general.back') }}
                        </a>
                        
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-white hover:bg-gray-700">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            {{ __('general.login') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ url('/') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                <i class="fas fa-home mr-1"></i>
                {{ __('general.back_to_home') }}
            </a>
        </div>
    </div>
</body>
</html> 