<?php

namespace App\Http\Requests;

use App\Models\Guest;
use Illuminate\Foundation\Http\FormRequest;

class GuestRequest extends FormRequest
{
    public function authorize(): bool
    {
        $guest = $this->route('guest');

        return $guest
            ? $this->user()?->can('update', $guest) ?? false
            : $this->user()?->can('create', Guest::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:32'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
