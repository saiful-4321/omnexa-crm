<?php

namespace App\Modules\Tenant\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandingRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Check if user belongs to this tenant and has permission
        return true; 
    }

    public function rules(): array
    {
        return [
            'logo' => 'nullable|string', // URL or base64 or file path rules
            'primary_color' => 'nullable|regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i',
            'secondary_color' => 'nullable|regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i',
            'config' => 'nullable|array',
            'config.font' => 'nullable|string',
        ];
    }
}
