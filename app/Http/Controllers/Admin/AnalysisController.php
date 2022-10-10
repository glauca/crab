<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        bcscale(2);

        $date = date('Y-m-d');
        if ($request->has('date')) {
            $date = date('Y-m-d', strtotime($request->get('date')));
        }

        $purchases = Purchase::with(['products'])
            ->whereDate('purchased_at', $date)
            ->orderBy('purchase_money', 'ASC')
            ->get();

        $weight = 0;
        $specs  = [];

        foreach ($purchases as $purchase) {
            foreach ($purchase->products as $product) {
                $weight = bcadd($weight, $product->pivot->stock);

                if (!isset($specs[$product->id])) {
                    $specs[$product->id] = [
                        'rank'  => $product->rank,
                        'total' => 0,
                        'name'  => $product->title,
                    ];
                }

                $specs[$product->id]['total'] = bcadd($specs[$product->id]['total'], $product->pivot->stock);
            }
        }

        $sort = [];

        foreach ($specs as $key => $spec) {
            $sort[$key] = $spec['rank'];
        }

        array_multisort($specs, $sort);

        $data = [
            'total_money' => $purchases->sum('purchase_money'),
            'weight'      => $weight,
            'pay_num'     => $purchases->count(),
            'purchases'   => $purchases,
            'specs'       => $specs,
        ];

        return successResponse($data);
    }
}
