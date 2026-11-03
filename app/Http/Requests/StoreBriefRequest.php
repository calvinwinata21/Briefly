<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBriefRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'event_title' => ['required', 'string', 'max:255'],
            'raw_brief_text' => ['required', 'string', 'min:10'],
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'event_title.required' => 'Judul kegiatan wajib diisi.',
            'event_title.max' => 'Judul kegiatan tidak boleh melebihi 255 karakter.',
            'raw_brief_text.required' => 'Brief mentah kegiatan wajib diisi.',
            'raw_brief_text.min' => 'Brief mentah minimal berisi 10 karakter agar dapat diproses oleh AI.',
        ];
    }
}
