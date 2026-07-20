<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'card_number' => ['required', 'string', 'regex:/^\\d{20}$/', Rule::unique('cards', 'card_number')],
            'pin' => ['required', 'string', 'regex:/^\\d{4}$/'],
            'activation_date' => ['required', 'date'],
            'expiration_date' => ['required', 'date', 'after_or_equal:activation_date'],
            'balance' => ['required', 'decimal:0,2', 'min:0'],
        ];
    }
}
