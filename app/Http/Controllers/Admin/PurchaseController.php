<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Vendor;
use Exception;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Purchase::with(['vendor', 'products' => function ($query) {

        }])
            ->latest('purchased_at')
            ->latest('id')
            ->paginate(10);

        return successResponse($items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $vendorId = (int) $request->get('vendor_id');
        $vendor   = Vendor::find($vendorId);
        if (!$vendor) {
            return errorResponse(400, '供应商不存在');
        }

        $money = (float) $request->get('purchase_money');
        $date  = $request->get('purchased_at');

        $purchase                 = new Purchase;
        $purchase->title          = $vendor->name . '-采购单';
        $purchase->purchase_money = abs($money);
        $purchase->purchased_at   = date('Y-m-d', strtotime($date));

        $vendor->purchases()->save($purchase);

        return successResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $purchase = Purchase::with(['vendor'])->findOrFail($id);

            return successResponse($purchase);
        } catch (Exception $e) {
            return errorResoponse(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $purchase = Purchase::find($id);

        if (!$purchase) {
            return errorResponse(404);
        }

        if ($request->has('purchase_money')) {
            $money = (float) $request->get('purchase_money');

            $purchase->purchase_money = abs($money);
        }

        if ($request->has('purchased_at')) {
            $date = $request->get('purchased_at');

            $purchase->purchased_at = date('Y-m-d', strtotime($date));
        }

        $purchase->save();

        return successResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $purchase = Purchase::findOrFail($id);

            $purchase->delete();

            return successResponse();
        } catch (Exception $e) {
            return errorResoponse(404);
        }
    }
}
