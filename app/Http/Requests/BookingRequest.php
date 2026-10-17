<?php

namespace App\Http\Requests;

use App\Models\Booking;
use App\Models\RoomType;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class BookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $booking = $this->route('booking');

        if ($booking) {
            return $this->user()?->can('update', $booking) ?? false;
        }

        return $this->user()?->can('create', Booking::class) ?? false;
    }

    public function rules(): array
    {
        $booking = $this->route('booking');

        return [
            'guest_id' => ['required', 'integer', Rule::exists('guests', 'id')],
            'room_type_id' => [
                'required',
                'integer',
                Rule::exists('room_types', 'id')->where(
                    fn (Builder $query) => $query->where('is_active', true)
                        ->when($booking, fn (Builder $query) => $query->orWhere('id', $booking->room_type_id)),
                ),
            ],
            'check_in' => ['required', 'date'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guest_count' => ['required', 'integer', 'between:1,255'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $roomType = RoomType::query()->find($this->integer('room_type_id'));

                if ($roomType && $this->integer('guest_count') > $roomType->capacity) {
                    $validator->errors()->add(
                        'guest_count',
                        "This room type accommodates up to {$roomType->capacity} guests.",
                    );
                }

                if ($roomType && $roomType->nightly_rate === null) {
                    $validator->errors()->add('room_type_id', 'Set a nightly rate for this room type before booking it.');
                }
            },
        ];
    }
}
