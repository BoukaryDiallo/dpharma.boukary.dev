<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePharmacistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('pharmacist'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $pharmacist = $this->route('pharmacist');
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                Rule::unique('users', 'email')->ignore($pharmacist->id)
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:pharmacist,manager,admin'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire',
            'email.required' => 'L\'adresse email est obligatoire',
            'email.email' => 'L\'adresse email n\'est pas valide',
            'email.unique' => 'Cette adresse email est déjà utilisée',
            'password.min' => 'Le mot de passe doit contenir au moins :min caractères',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas',
        ];
    }
}
