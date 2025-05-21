<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TranslationRequest;
use App\Http\Responses\Api\ApiResponse;
use App\Models\Pack;
use App\Models\Translation;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    /**
     * Retrieve a list of translations.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, int $pack): ApiResponse
    {
        $count = $request->query('count', 0);
        $translations = Translation::where('pack_id', $pack)->inRandomOrder();

        if ($count) {
            $translations = $translations->limit($count);
        }

        return ApiResponse::success($translations->get());
    }
    
    /**
     * Create a new translation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TranslationRequest $request, Pack $pack): ApiResponse
    {
        // Check if the pack belongs to the user
        // if (! $this->isPackOwner($pack)) {
        //     return ApiResponse::error(403, 'Unauthorized');
        // }

        $validatedData = $request->validated();

        // Check if the translation already exists
        $translationExists = Translation::where([
            'pack_id' => $pack->id,
            'from_translation' => strtolower($validatedData['from_translation']),
            'to_translation' => strtolower($validatedData['to_translation']),
            ])->exists();

        if ($translationExists) {
            return ApiResponse::error(400, 'Translation already exists');
        }

        try {
            $validatedData['pack_id'] = $pack->id;
            $validatedData['from_translation'] = $validatedData['from_translation'];
            $validatedData['to_translation'] = $validatedData['to_translation'];
            $translation = Translation::create($validatedData);
        } catch (\Exception $e) {
            return ApiResponse::error(500, 'Failed to create translation');
        }

        return ApiResponse::success($translation, 201);
    }

    /**
     * Update an existing translation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Translation  $translation
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TranslationRequest $request, Pack $pack, Translation $translation): ApiResponse
    {
        // Check if the pack belongs to the user
        if (! $this->isPackOwner($pack)) {
            return ApiResponse::error(403, 'Unauthorized');
        }

        $validatedData = $request->validated();

        // Check if the translation already exists
        $translationExists = Translation::where([
            'pack_id' => $pack->id,
            'from_translation' => $validatedData['from_translation'],
            'to_translation' => $validatedData['to_translation'],
            ])->where('id', '!=', $translation->id)->exists();

        if ($translationExists) {
            return ApiResponse::error(400, 'Translation already exists');
        }

        try {
            $translation->update($validatedData);
        } catch (\Exception $e) {
            return ApiResponse::error(500, 'Failed to update translation');
        }

        return ApiResponse::success($translation);
    }

    /**
     * Delete a translation.
     *
     * @param  \App\Models\Translation  $translation
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Pack $pack, Translation $translation): ApiResponse
    {
        // Check if the pack belongs to the user
        if (! $this->isPackOwner($pack)) {
            return ApiResponse::error(403, 'Unauthorized');
        }
        
        try {
            $translation->delete();
        } catch (\Exception $e) {
            return ApiResponse::error(500, 'Failed to delete translation');
        }

        return ApiResponse::success(null, 204);
    }

    /**
     * Check if the pack belongs to the user.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function isPackOwner(Pack $pack): bool
    {
        $user = CurrentUser();

        return $pack->user_id === $user->id;
    }   
}
