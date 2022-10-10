<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use Exception;
use Illuminate\Http\Request;

class PurchaseProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $purchase = Purchase::with([
            'products' => function ($query) {
                return $query->oldest('type')
                    ->oldest('rank')
                    ->oldest('id');
            },
        ])->find($id);

        return successResponse($purchase);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        try {
            $purchase = Purchase::findOrFail($id);

            $productId = (int) $request->get('product_id');

            $product = Product::findOrFail($productId);

            $data = [
                'price' => (float) $request->get('price'),
                'stock' => (float) $request->get('stock'),
            ];

            $purchase->products()->attach($product, $data);

            return successResponse();
        } catch (Exception $e) {
            return errorResponse(404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $productId)
    {
        try {
            $purchase = Purchase::findOrFail($id);

            $product = Product::findOrFail($productId);

            $data = [
                'price' => (float) $request->get('price'),
                'stock' => (float) $request->get('stock'),
            ];

            $purchase->products()->updateExistingPivot($product, $data);

            return successResponse();
        } catch (Exception $e) {
            return errorResponse(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $productId)
    {
        try {
            $purchase = Purchase::findOrFail($id);

            $product = Product::findOrFail($productId);

            $purchase->products()->detach($product);

            return successResponse();
        } catch (Exception $e) {
            return errorResponse(404);
        }
    }
}
