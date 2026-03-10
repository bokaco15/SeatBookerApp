<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HallStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|unique:halls,name|max:64|min:3',
            'rows' => 'required|integer|min:1|max:100',
            'columns' => 'required|integer|min:1|max:100',
            'is_active' => 'nullable|boolean',
        ];
    }
}
