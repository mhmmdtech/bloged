<?php

namespace App\Http\Requests;

use App\Enums\MilitaryStatus;
use App\Models\User;
use App\Rules\IrMobileNumber;
use App\Rules\IrNationalCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use App\Enums\GenderStatus;
use App\Rules\DontStartWithNumbers;
use Illuminate\Support\Carbon;

class ProfileUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'alpha:ascii', 'min:2', 'max:100'],
            'last_name' => ['required', 'string', 'alpha:ascii', 'min:2', 'max:100'],
            'national_code' => ['required', 'numeric', 'digits_between:5,100', new IrNationalCode, Rule::unique(User::class)->ignore($this->user())],
            'mobile_number' => ['required', 'string', 'min:5', 'max:100', new IrMobileNumber],
            'gender' => ['required', new Rules\Enum(GenderStatus::class)],
            'email' => ['required', 'string', 'email', 'min:5', 'max:255', Rule::unique(User::class)->ignore($this->user())],
            'username' => ['required', 'string', 'alpha_num:ascii', 'min:2', 'max:100', new DontStartWithNumbers, Rule::unique(User::class)->ignore($this->user())],
            'avatar' => ['nullable', Rules\File::image()->max(200)],
            'birthday' => ['nullable', 'date', 'before_or_equal:' . Carbon::now()->subYears(10)->toDateString()],
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