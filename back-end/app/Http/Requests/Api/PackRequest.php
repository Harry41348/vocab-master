<?php

namespace App\Http\Requests\Api;

use App\Models\Pack;

class PackRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Check if the user is authenticated
        if ($this->user() === null) {
            return false;
        }

        // Check if the user is the owner of the pack
        $pack = Pack::find($this->route('pack'));
        if ($pack && $this->user()->id !== $pack->user_id) {
            return false;
        }

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
