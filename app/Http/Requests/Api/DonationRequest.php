<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class DonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'exists:projects,id'],
            'nickname' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email'],
            'value' => ['required', 'numeric', 'min:0.01'],
            'message' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'in:mercadopago,manual'],
            'proof_file' => ['required_if:payment_method,manual', 'file', 'mimes:pdf,png,jpg,jpeg', 'max:2048']
        ];
    }
}
