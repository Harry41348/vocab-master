<?php

namespace App\Http\Requests\Api;

use App\Models\Translation;
use Illuminate\Validation\Validator;

class TranslationRequest extends ApiRequest
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
        $pack = $this->route('pack');
        if ($this->user()->id !== $pack->user_id) {
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
            'from_translation' => 'required|string',
            'to_translation' => 'required|string'
        ];
    }
    
    /**
     * Get the "after" validation callables for the request.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $exists = Translation::where([
                    'pack_id' => $this->route('pack')->id,
                    'from_translation' => strtolower($this->input('from_translation')),
                    'to_translation' => strtolower($this->input('to_translation')),
                ])->exists();

                if ($exists) {
                    $validator->errors()->add(
                        'translation',
                        'Translation already exists'
                    );
                }
            }
        ];
    }
}
