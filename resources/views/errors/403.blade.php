<x-app-layout>
    <div class="min-h-screen flex flex-col items-center justify-center">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 max-w-md w-full">
            <div class="text-center">
                <i class="fas fa-lock text-6xl text-red-500 mb-4"></i>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('errors.403.title') }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    {{ __('errors.403.message') }}
                </p>
                <a href="{{ url()->previous() }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700">
                    <i class="fas fa-arrow-left mr-2"></i>
                    {{ __('general.back') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout> 