<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HallUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'rows' => 'required|integer|min:1',
            'columns' => 'required|integer|min:1',
        ];
    }
}
