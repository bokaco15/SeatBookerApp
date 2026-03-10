<?php

namespace App\Http\Requests;

use App\Enums\Event\EventType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class EventUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'hall_id'    => 'required|exists:halls,id',
            'name'       => 'required|string|min:3|max:255',
            'type'       => ['required', new Enum(EventType::class)],
            'date'       => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
            'status'     => 'nullable|string',
        ];
    }
}
