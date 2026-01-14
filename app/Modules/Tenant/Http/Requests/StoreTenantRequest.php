<?php

namespace App\Modules\Tenant\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTenantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Typically only Super Admins can create tenants
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'subdomain' => 'required|string|alpha_dash|max:50|unique:tenants,subdomain',
            'custom_domain' => 'nullable|string|unique:tenants,custom_domain',
            'email' => 'required|email',
            'storage_limit' => 'nullable|integer|min:1024', // MB
        ];
    }
}
