<?php

namespace App\Http\Requests;

use App\Models\Room;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        $room = $this->route('room');

        return $room
            ? $this->user()?->can('update', $room) ?? false
            : $this->user()?->can('create', Room::class) ?? false;
    }

    public function rules(): array
    {
        $room = $this->route('room');

        return [
            'room_type_id' => ['required', 'integer', Rule::exists('room_types', 'id')],
            'room_number' => [
                'required',
                'string',
                'max:30',
                Rule::unique('rooms', 'room_number')->ignore($room?->id),
            ],
            'floor' => ['nullable', 'integer', 'between:0,32767'],
            'status' => [
                'required',
                Rule::in([
                    Room::STATUS_AVAILABLE,
                    Room::STATUS_OCCUPIED,
                    Room::STATUS_MAINTENANCE,
                ]),
            ],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
