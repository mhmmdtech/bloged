<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Enums;
use App\Rules as CustomRule;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class RegisterationRequest extends FormRequest
{
    // private $tenYearsAgo = Carbon::now()->subYear(10)->toDateString();
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
            'first_name' => ['required', 'string', new CustomRule\AlphaSpace, 'min:2', 'max:100'],
            'last_name' => ['required', 'string', new CustomRule\AlphaSpace, 'min:2', 'max:100'],
            'national_code' => ['required', 'numeric', 'digits_between:5,100', new CustomRule\IrNationalCode, Rule::unique(User::class)],
            'mobile_number' => ['required', 'string', 'min:5', 'max:100', new CustomRule\IrMobileNumber],
            'gender' => ['required', new Rules\Enum(Enums\GenderStatus::class)],
            'email' => ['required', 'string', 'email', 'min:5', 'max:255', Rule::unique(User::class)],
            'username' => ['required', 'string', 'alpha_num:ascii', 'min:2', 'max:100', new CustomRule\DontStartWithNumbers, Rule::unique(User::class)],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'avatar' => ['nullable', Rules\File::image()->max(200)],
            'birthday' => ['nullable', 'date', 'before_or_equal:' . Carbon::now()->subYears(10)->toDateString()],
            'military_status' => ['nullable', 'required_if:gender,' . Enums\GenderStatus::Male->value, new Rules\Enum(Enums\MilitaryStatus::class)],
            'province_id' => ['nullable', 'exists:provinces,id'],
            'city_id' => ['nullable', 'required_with:province_id', 'exists:cities,id'],
            'captcha_code' => ['required', 'numeric', 'digits_between:6,8', new CustomRule\CaptchaValidator]
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

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'province_id' => 'province',
            'city_id' => 'city',
        ];
    }
}