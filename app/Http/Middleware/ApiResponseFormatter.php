<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiResponseFormatter
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $data = $response->getData(true);
            
            $formattedResponse = [
                'success' => $response->status() < 400,
                'data' => $data,
                'timestamp' => now()->toIso8601String()
            ];

            $response->setData($formattedResponse);
        }

        return $response;
    }
}
