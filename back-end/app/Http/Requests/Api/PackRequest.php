<?php

namespace App\Http\Requests\Api;

class PackRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Allow all users to create packs
        return true;
    }

    /**
     * Ensure the pack fields are valid.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $packId = $this->route('pack');

        return [
            'name' => 'required|string|unique:packs,name,'.$packId,
            'description' => 'nullable|string',
            'language_from' => 'required',
            'language_to' => 'required',
        ];
    }
}
