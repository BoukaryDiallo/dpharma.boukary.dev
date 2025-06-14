<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePharmacistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['sometimes', 'string', Rule::in(['pharmacist', 'manager', 'admin'])],
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
            'password.required' => 'Le mot de passe est obligatoire',
            'password.min' => 'Le mot de passe doit contenir au moins :min caractères',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas',
        ];
    }
}
