<?php

namespace App\Http\Requests;

use App\Models\Card;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var Card $card */
        $card = $this->route('card');

        return $this->user()?->can('update', $card) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        /** @var Card $card */
        $card = $this->route('card');

        return [
            'card_number' => ['required', 'string', 'regex:/^\\d{20}$/', Rule::unique('cards', 'card_number')->ignore($card)],
            'pin' => ['required', 'string', 'regex:/^\\d{4}$/'],
            'activation_date' => ['required', 'date'],
            'expiration_date' => ['required', 'date', 'after_or_equal:activation_date'],
            'balance' => ['required', 'decimal:0,2', 'min:0'],
        ];
    }
}
