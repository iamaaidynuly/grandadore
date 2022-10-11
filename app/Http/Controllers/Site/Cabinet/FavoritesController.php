<?php

namespace App\Http\Controllers\Site\Cabinet;

use App\Http\Controllers\Site\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FavoritesController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFavorites(Request $request)
    {
        $items = $request->user()->favorites()->pluck('item_id')->toArray();

        return response()->json($items);
    }

    /**
     * @param Request $request
     * @return Response|mixed|object
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function add(Request $request)
    {
        $this->validate($request, [
            'itemId' => 'required|numeric|exists:items,id'
        ]);

        /** @var User $user */
        $user = $request->user();

        $user->favorites()->updateOrCreate([
            'item_id' => $request->input('itemId')
        ]);

        return response()->make('Added')->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|Response|mixed|object
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function destroy(Request $request)
    {
        $this->validate($request, [
            'itemId' => 'required|numeric|exists:user_favorite,item_id'
        ]);

        /** @var User $user */
        $user = $request->user();

        $response = $user->favorites()->where('item_id', $request->input('itemId'))->delete();

        if (!$response) {
            return response()->json([
                'message' => 'Error'
            ])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->make('Deleted')->setStatusCode(Response::HTTP_OK);
    }
}

