<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Services\MercadoPagoService;
use Illuminate\Support\Facades\RateLimiter;

class PaymentNotificationController extends Controller
{
    public function checkPaymentStatus($external_reference)
    {
        if (RateLimiter::tooManyAttempts('payment-check:'.$external_reference, 10)) {
            return response()->json(['error' => 'Too many attempts'], 429);
        }

        RateLimiter::hit('payment-check:'.$external_reference);

        $response = $this->handleNotification($external_reference);
        $verify_payment = json_decode(json_encode($response->getData()), true);

        if (isset($verify_payment['status']) && $verify_payment['status'] === 200) {

            // Usando htmlspecialchars() para sanitização
            $externalReference = htmlspecialchars($external_reference);


            $isPaymentApproved = Donation::where('external_reference', $externalReference)
                ->where('status', 'approved')
                ->exists();


            return response()->json(['status' => $isPaymentApproved ? 'approved' : 'pending']);
        }
        return response()->json(['status' => 'pending']);
    }

    private function handleNotification($external_reference)
    {
        $paymentData = new MercadoPagoService();
        $paymentData = $paymentData->getPaymentStatus($external_reference);

        // Check if results exist
        if (!isset($paymentData['results']) || empty($paymentData['results'])) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        $donation = Donation::where('external_reference', $paymentData['results'][0]['external_reference'])->first();

        if (!$donation) {
            return response()->json(['error' => 'Payment data mismatch'], 400);
        }

        if ($paymentData['results'][0]['status'] == "approved") {

            $donation->update([
                'status' => $paymentData['results'][0]['status'],
                'updated_at' => now(),
            ]);

            return response()->json(['status' => 200, 'message' => 'Payment status updated successfully']);
        }

        return response()->json(['status' => 404, 'message' => 'Payment status pending']);
    }

}
