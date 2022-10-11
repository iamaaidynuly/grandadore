<?php

namespace App\Http\Controllers\Site\Cabinet;

use App\Http\Controllers\Site\BaseController;
use App\Models\Items;
use App\Models\ItemSizes;
use App\Services\BasketService\BasketFactory;
use App\ValueObjects\BasketItem;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class BasketController extends BaseController
{
    /**
     * @return JsonResponse
     */
    public function getBasketItems()
    {
        $basketService = BasketFactory::createDriver();
        $response = $basketService->getItems();

        return response()->json($response->toArray());
    }

    public function getSmallBasket()
    {
        return response()->view('site.components.small-basket.constructor');
    }

    /**
     * @param Request $request
     * @return Response|mixed|object
     * @throws BindingResolutionException
     * @throws ValidationException
     */
    public function add(Request $request)
    {

        $this->validate($request, [
            'itemId' => 'required|exists:items,id',
            'count' => 'required|numeric|min:1'
        ]);

        $item = Items::getActiveItem($request->input('itemId'));

        $data = [
            'count' => $request->input('count'),
        ];

        if ($request->has('sizeId')) {
            $data['size'] = $request->input('sizeId');
        }

        if ($request->has('colorId')) {
            $data['color'] = $request->input('colorId');
        }

        if (!$item) {
            return response()->make('Failed')->setStatusCode(Response::HTTP_NOT_FOUND);
        } elseif (!$item->price) {
            return response()->make('Failed')->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $basketService = BasketFactory::createDriver();
        if (!$basketService->add($item->id, $data)) {
            return response()->make('Failed')->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->make('Added')->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @return Response|mixed|object
     * @throws BindingResolutionException
     * @throws ValidationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'itemId' => 'required|exists:items,id',
            'count' => 'required|numeric|min:1'
        ]);

        $item = Items::getActiveItem($request->input('itemId'));

        if (!$item) {
            return response()->make('Failed')->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $count = $request->input('count');

        $basketService = BasketFactory::createDriver();

        $result = $basketService->update($item->id, [
            'count' => $count
        ]);

        if (!$result) {
            return response()->make('Failed')->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->make('Updated')->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse|Response|mixed|object
     * @throws BindingResolutionException
     * @throws ValidationException
     */
    public function delete(Request $request)
    {
        $this->validate($request, [
            'itemId' => 'required|exists:items,id'
        ]);

        $basketService = BasketFactory::createDriver();

        if (!$basketService->delete($request->input('itemId'))) {
            return response()->json([
                'message' => 'Error'
            ])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->make('Deleted')->setStatusCode(Response::HTTP_OK);
    }
}
