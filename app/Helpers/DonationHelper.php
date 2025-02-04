<?php

namespace App\Helpers;

use Piggly\Pix\Enums\QrCode;
use Piggly\Pix\Parser;
use Piggly\Pix\StaticPayload;

class DonationHelper
{
    public static function getStatusLabel(string $status)
    {
        return __('donations.status.' . $status);
    }

    public static function getPaymentMethodLabel(string $method)
    {
        return __('donations.payment_method.' . $method);
    }

    public static function getStatusClasses(string $status)
    {
        return match ($status) {
            'pending' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
            'approved' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
            'rejected' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
            default => 'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200'
        };
    }

    public static function getStatusIcon(string $status)
    {
        return match ($status) {
            'pending' => '<i class="fas fa-clock mr-2"></i>',
            'approved' => '<i class="fas fa-check-circle mr-2"></i>',
            'rejected' => '<i class="fas fa-times-circle mr-2"></i>',
            default => '<i class="fas fa-circle mr-2"></i>'
        };
    }

    public static function formatMoneyValue(mixed $value): string
    {
        if (is_string($value)) {
            $value = str_replace(',', '.', $value);
        }

        return 'R$ ' . number_format((float)$value, 2, ',', '.');
    }

    public static function getPixKeyQrCode(): string
    {
        return (new StaticPayload())
            ->setPixKey(Parser::getKeyType(env('PIX_KEY')), env('PIX_KEY'))
            ->setMerchantName(env('PIX_BENEFICIARY'))
            ->setMerchantCity(env('PIX_BENEFICIARY_CITY'))
            ->getQRCode(QrCode::OUTPUT_PNG);
    }

    public static function convertMoneyToCents(string $value): int
    {
        $value = preg_replace('/[^\d]/', '', $value);

        if (strlen($value) < 3) {
            $value = str_pad($value, 3, '0', STR_PAD_RIGHT);
        }

        return (int) $value;
    }
}
