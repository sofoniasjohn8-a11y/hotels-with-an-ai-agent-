<?php

namespace App\Http\Requests;

use App\Models\RoomType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoomTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $roomType = $this->route('roomType');

        return $roomType
            ? $this->user()?->can('update', $roomType) ?? false
            : $this->user()?->can('create', RoomType::class) ?? false;
    }

    public function rules(): array
    {
        $roomType = $this->route('roomType');

        return [
            'name' => ['required', 'string', 'max:120'],
            'slug' => [
                'required',
                'alpha_dash',
                'max:140',
                Rule::unique('room_types', 'slug')->ignore($roomType?->id),
            ],
            'description' => ['nullable', 'string', 'max:5000'],
            'capacity' => ['required', 'integer', 'between:1,255'],
            'nightly_rate' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'amenities' => ['nullable', 'array', 'max:30'],
            'amenities.*' => ['required', 'string', 'max:100'],
            'photos' => ['nullable', 'array', 'max:10'],
            'photos.*' => ['required', 'url', 'max:2048'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
