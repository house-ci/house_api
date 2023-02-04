<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\StoreOwnerRequest;
use App\Http\Requests\UpdateOwnerRequest;
use App\Models\Commands\Owner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class OwnerController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreOwnerRequest $request
     * @return JsonResponse
     */
    public function store(StoreOwnerRequest $request): JsonResponse
    {

        try {
            $owner = Owner::create($request->validated());
            if ($owner) {
                return response()->json(ApiResponse::getRessourceSuccess(201, $owner), 201);
            }
        } catch (\Exception $e) {
            info($e->getMessage());
        }
        $error = 'An Error Occurred while storing';
        return response()->json(ApiResponse::error(500, $error), 500);
    }

    /**
     * Display the specified resource.
     *
     * @param Owner $owner
     * @return JsonResponse
     */
    public function show(Request $request) :JsonResponse
    {
        $owner = ($request->owner ?? $request->get('owner'));

        if (!empty($owner)) {
            return response()->json(ApiResponse::getRessourceSuccess(200, $owner));
        }
        return response()->json(ApiResponse::NOTFOUND, 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Owner $owner
     * @return Response
     */
    public function edit(Owner $owner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateOwnerRequest $request
     * @param Owner $owner
     * @return Response
     */
    public function update(UpdateOwnerRequest $request, Owner $owner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Owner $owner
     * @return Response
     */
    public function destroy(Owner $owner)
    {
        //
    }
}
