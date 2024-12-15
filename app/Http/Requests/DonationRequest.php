<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'project_id' => 'required|exists:projects,id',
            'nickname' => ['required', 'string', 'min:2', 'max:255'],
            'email' => 'required|email',
            'message' => ['nullable', 'string', 'max:1000'],
            'value' => ['required'],
            'payment_method' => 'required|in:mercadopago,manual',
        ];

        if ($this->input('payment_method') === 'manual') {
            $rules['proof_file'] = 'required|file|mimes:pdf,png,jpg,jpeg|max:2048';
        }

        return $rules;
    }

    /**
     * Custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'project_id.required' => 'O projeto é obrigatório.',
            'project_id.exists'   => 'O projeto selecionado não é válido.',
            'nickname.required'   => 'O apelido é obrigatório.',
            'nickname.min'        => 'O apelido deve ter no mínimo :min caracteres.',
            'nickname.max'        => 'O apelido deve ter no máximo :max caracteres.',
            'email.required'      => 'O e-mail é obrigatório.',
            'email.email'         => 'Por favor, insira um endereço de e-mail válido.',
            'value.required'      => 'O valor da doação é obrigatório.',
            'value.numeric'       => 'O valor da doação deve ser numérico. Utilize o formato correto.',
            'value.min'           => 'O valor mínimo de doação é de R$ 0,01.',
            'message.max'         => 'A mensagem não pode ter mais de :max caracteres.',
            'proof_file.required_if' => 'O comprovante de pagamento é obrigatório para doações manuais.',
            'proof_file.mimes' => 'O comprovante deve ser um arquivo PDF, PNG ou JPG.',
            'proof_file.max' => 'O comprovante não pode ter mais que 2MB.'
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422)
        );
    }

    protected function prepareForValidation()
    {
        if ($this->has('value')) {
            $value = $this->value;
            
            // Se o valor já estiver no formato correto (com ponto decimal)
            if (is_numeric($value)) {
                $value = (float) $value;
            } else {
                // Remover pontos de milhar e trocar vírgula por ponto
                $value = str_replace('.', '', $value);
                $value = str_replace(',', '.', $value);
                $value = (float) $value;
            }
            
            $this->merge([
                'value' => $value // Manter como float para o Mercado Pago
            ]);
        }
    }
}
