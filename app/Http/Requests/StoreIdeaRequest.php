<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\IdeaStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreIdeaRequest extends FormRequest
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
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'status' => ['required', 'string', Rule::enum(IdeaStatus::class)],
            'description' => ['string', 'nullable'],
            'links' => ['array', 'nullable'],
            'links.*' => ['url'],
            'steps' => ['array', 'nullable'],
            'steps.*.description' => ['string', 'max:255'],
            'steps.*.completed' => ['boolean'],
            'image' => ['nullable' ,'max:5120'],
        ];
    }
}
