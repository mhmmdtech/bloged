<?php

namespace App\Http\Requests\Admin;

use App\Enums\PostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class StorePostRequest extends FormRequest
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
            'title' => ['required', 'string', 'min:2', 'max:100'],
            'seo_title' => ['required', 'string', 'min:2', 'max:100'],
            'description' => ['required', 'string', 'min:5', 'max:255'],
            'seo_description' => ['required', 'string', 'min:5', 'max:255'],
            'body' => ['required', 'string', 'min:250'],
            'html_content' => ['required', 'string', 'min:250'],
            'status' => ['required', new Rules\Enum(PostStatus::class)],
            'thumbnail' => ['required', Rules\File::image()->min(0.5 * 1024)->max(5 * 1024)->dimensions(Rule::dimensions()->minWidth(320)->minHeight(320))],
            'category_id' => 'required|exists:categories,id',
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
            'category_id' => 'category',
        ];
    }
}