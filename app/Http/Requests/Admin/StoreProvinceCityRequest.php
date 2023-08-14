<?php

namespace App\Http\Requests\Admin;

use App\Enums\ProvinceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class StoreProvinceCityRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'local_name' => ['required', 'string', 'min:2', 'max:100'],
            'latin_name' => ['nullable', 'string', 'alpha:ascii', 'min:2', 'max:100'],
            'status' => ['required', new Rules\Enum(ProvinceStatus::class)],
        ];
    }
}