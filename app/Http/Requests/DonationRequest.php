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
        return [
            'project_id' => 'required|exists:projects,id',
            'nickname'   => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\p{L}\s\-0-9]+$/u'],
            'email'      => 'required|email:rfc,dns',
            'value'      => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'message'    => ['nullable', 'string', 'max:500', 'regex:/^[\p{L}\s\-0-9.,!?]+$/u'],
        ];
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
        ];
    }
}
