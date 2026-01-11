<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // The middleware 'auth' is already applied on the controller
        // So if we reach here, the user is authenticated.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'passenger_first_name' => 'required|string|max:255|not_in:Non spécifié,non spécifié,Non spécifiée,non spécifiée',
            'passenger_last_name' => 'required|string|max:255|not_in:Non spécifié,non spécifié,Non spécifiée,non spécifiée',
            'passenger_phone' => 'required|string|max:20|not_in:Non spécifié,non spécifié,Non spécifiée,non spécifiée',
            'passenger_email' => 'nullable|email|max:255|not_in:Non spécifié,non spécifié,Non spécifiée,non spécifiée',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'passenger_first_name.not_in' => 'Le prénom ne peut pas être "Non spécifié".',
            'passenger_last_name.not_in' => 'Le nom ne peut pas être "Non spécifié".',
            'passenger_phone.not_in' => 'Le numéro de téléphone ne peut pas être "Non spécifié".',
            'passenger_email.not_in' => 'L\'email ne peut pas être "Non spécifié".',
        ];
    }
}
