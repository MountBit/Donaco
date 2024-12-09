@props(['type' => 'success', 'message' => ''])

@php
    $bgColor = $type === 'success' ? 'bg-green-500' : 'bg-red-500';
    $textColor = 'text-white';
@endphp

<div id="alert" class="{{ $bgColor }} {{ $textColor }} p-4 mb-4 text-sm rounded-lg shadow-lg flex items-center space-x-4 transition-transform transform scale-100 max-w-sm" role="alert">
    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        @if ($type === 'success')
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        @else
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        @endif
    </svg>
    <div class="flex-1">
        <strong class="font-medium">{{ ucfirst($type) }}!</strong> {{ $message }}
    </div>
    <button onclick="closeAlert()" class="ml-4 text-white hover:text-gray-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
</div>
