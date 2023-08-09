<?php

namespace App\Http\Requests\Auth;

use App\Enums\GenderStatus;
use App\Models\User;
use App\Rules\DontStartWithNumbers;
use App\Rules\IrMobileNumber;
use App\Rules\IrNationalCode;
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
            'first_name' => ['required', 'string', 'alpha:ascii', 'min:2', 'max:100'],
            'last_name' => ['required', 'string', 'alpha:ascii', 'min:2', 'max:100'],
            'national_code' => ['required', 'numeric', 'digits_between:5,100', new IrNationalCode, Rule::unique(User::class)],
            'mobile_number' => ['required', 'string', 'min:5', 'max:100', new IrMobileNumber],
            'gender' => ['required', new Rules\Enum(GenderStatus::class)],
            'email' => ['required', 'string', 'email', 'min:5', 'max:255', Rule::unique(User::class)],
            'username' => ['required', 'string', 'alpha_num:ascii', 'min:2', 'max:100', new DontStartWithNumbers, Rule::unique(User::class)],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'avatar' => ['nullable', Rules\File::image()->max(200)],
            'birthday' => ['nullable', 'date', 'before_or_equal:' . Carbon::now()->subYears(10)->toDateString()],
        ];
    }
}