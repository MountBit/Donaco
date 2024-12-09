<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MercadoPagoService
{
    protected $accessToken;

    public function __construct()
    {
        $this->accessToken = config('mercadopago.access_token'); // Defina isso no arquivo de config
    }

    public function createPayment($nickname, $email, $value, $externalReference)
    {
        $payer = [
            "first_name" => $nickname,
            "email"      => $email
        ];

        $informations = [
            "notification_url"   => config('mercadopago.notification_url'),
            "description"        => "Doação de {$nickname}",
            "external_reference" => $externalReference,
            "transaction_amount" => $value,
            "payment_method_id"  => "pix"
        ];

        $payment = array_merge(["payer" => $payer], $informations);

        $response = Http::withToken($this->accessToken)
            ->post('https://api.mercadopago.com/v1/payments', $payment);

        if ($response->failed()) {
            $originalErrorMessage = $response->json('message');

            $errorMessage = __('errors.' . $originalErrorMessage, ['message' => $originalErrorMessage]) ?? __('errors.generic_error');

            throw new \Exception($errorMessage);
        }

        $transactionData = $response->json()['point_of_interaction']['transaction_data'];

        return [
            'qr_code' => $transactionData['qr_code'],
            'qr_code_base64' => $transactionData['qr_code_base64'],
            'ticket_url' => $transactionData['ticket_url']
        ];
    }

    public function getPaymentStatus($paymentId)
    {

        if (!$paymentId) {
            return response()->json(['error' => 'Payment ID not provided'], 400);
        }

        $response = Http::withToken(config('mercadopago.access_token'))
            ->get("https://api.mercadopago.com/v1/payments/search?external_reference={$paymentId}");

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to retrieve payment data'], 500);
        }

        return $response->json();
    }
}
