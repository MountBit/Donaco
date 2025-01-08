<?php

namespace App\Helpers;

class DonationHelper
{
    public static function getStatusLabel($status)
    {
        return __('donations.status.' . $status);
    }

    public static function getPaymentMethodLabel($method)
    {
        return __('donations.payment_method.' . $method);
    }

    public static function getStatusClasses($status)
    {
        return match ($status) {
            'pending' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
            'approved' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
            'rejected' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
            default => 'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200'
        };
    }

    public static function getStatusIcon($status)
    {
        return match ($status) {
            'pending' => '<i class="fas fa-clock mr-2"></i>',
            'approved' => '<i class="fas fa-check-circle mr-2"></i>',
            'rejected' => '<i class="fas fa-times-circle mr-2"></i>',
            default => '<i class="fas fa-circle mr-2"></i>'
        };
    }

    /**
     * Formata um valor monetário para exibição
     *
     * @param string|float $value
     * @return string
     */
    public static function formatMoneyValue($value): string
    {
        // Se o valor vier como string com vírgula, converte para float
        if (is_string($value)) {
            $value = str_replace(',', '.', $value);
        }
        
        return 'R$ ' . number_format((float)$value, 2, ',', '.');
    }
} 