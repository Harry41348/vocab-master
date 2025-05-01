<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PackRequest;
use App\Http\Responses\Api\ApiResponse;
use App\Models\Pack;

class PackController extends Controller
{
    /**
     * Retrieve a list of all packs.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): ApiResponse
    {
        $packs = Pack::all();

        return ApiResponse::success($packs);
    }

    /**
     * Retrieve a specific pack by its ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): ApiResponse
    {
        $pack = Pack::find($id);

        if (! $pack) {
            return ApiResponse::error(404, 'Pack not found');
        }

        return ApiResponse::success($pack);
    }

    /**
     * Create a new pack.
     *
     * @param  \Illuminate\Http\PackRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PackRequest $request): ApiResponse
    {
        $validatedData = $request->validated();

        try {
            $validatedData['user_id'] = CurrentUser()->id;
            $pack = Pack::create($validatedData);
        } catch (\Exception $e) {
            return ApiResponse::error(500, 'Failed to create pack');
        }

        return ApiResponse::success($pack, 201);
    }

    /**
     * Update an existing pack.
     *
     * @param  \Illuminate\Http\PackRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PackRequest $request, $id): ApiResponse
    {
        $pack = Pack::find($id);

        if (! $pack) {
            return ApiResponse::error(404, 'Pack not found');
        }

        // Check if the authenticated user is the owner of the pack
        if (! $this->isOwner($pack)) {
            return ApiResponse::error(403, 'You do not have permission to update this pack');
        }

        $validatedData = $request->validated();

        try {
            $pack->update($validatedData);
        } catch (\Exception $e) {
            return ApiResponse::error(500, 'Failed to update pack');
        }

        return ApiResponse::success($pack);
    }

    /**
     * Delete a pack.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): ApiResponse
    {
        $pack = Pack::find($id);

        if (! $pack) {
            return ApiResponse::error(404, 'Pack not found');
        }

        // Check if the authenticated user is the owner of the pack
        if (! $this->isOwner($pack)) {
            return ApiResponse::error(403, 'You do not have permission to delete this pack');
        }

        try {
            $pack->delete();
        } catch (\Exception $e) {
            return ApiResponse::error(500, 'Failed to delete pack');
        }

        return ApiResponse::success(null, 204);
    }

    private function isOwner(Pack $pack): bool
    {
        return $pack->user_id === CurrentUser()->id;
    }
}
