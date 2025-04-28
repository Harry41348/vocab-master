<?php

namespace App\Http\Requests\Api;

class PackRequest extends ApiRequest
{
    /**
     * Ensure the pack fields are valid.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:packs',
            'description' => 'nullable|string',
            'language_from' => 'required',
            'language_to' => 'required',
          ];
    }
}
