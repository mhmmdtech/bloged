<?php

namespace App\Http\Requests\Admin;

use App\Enums\GenderStatus;
use App\Enums\MilitaryStatus;
use App\Models\User;
use App\Rules\DontStartWithNumbers;
use App\Rules\IrMobileNumber;
use App\Rules\IrNationalCode;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class AdvancedSearchUserRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'alpha:ascii', 'min:2', 'max:100'],
            'last_name' => ['required', 'string', 'alpha:ascii', 'min:2', 'max:100'],
            'national_code' => ['required', 'numeric', 'digits_between:5,100', new IrNationalCode, Rule::unique(User::class)],
            'mobile_number' => ['required', 'string', 'min:5', 'max:100', new IrMobileNumber],
            'email' => ['required', 'string', 'email', 'min:5', 'max:255', Rule::unique(User::class)],
            'username' => ['required', 'string', 'alpha_num:ascii', 'min:2', 'max:100', new DontStartWithNumbers, Rule::unique(User::class)],
            'creator_id' => ['required', 'exists:users,id'],
            'birthday' => ['nullable', 'date', 'before_or_equal:' . Carbon::now()->subYears(10)->toDateString()],
            'created_at' => ['nullable', 'date', 'before_or_equal:' . Carbon::now()->toDateString()],
            'gender' => ['required', new Rules\Enum(GenderStatus::class)],
            'military_status' => ['nullable', 'required_if:gender,' . GenderStatus::Male->value, new Rules\Enum(MilitaryStatus::class)],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'military_status.required_if' => 'The military status field is required when gender is male.',
        ];
    }
}