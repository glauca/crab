<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sortField = $request->get('sortField', null);
        $sortOrder = $request->get('sortOrder', null);

        $name    = $request->get('name');
        $regions = $request->get('regions', []);

        $vendors = Vendor::with(['province', 'city', 'dist'])
            ->when($sortField, function ($query, $sortField) use ($sortOrder) {
                return $query->orderBy($sortField, $sortOrder == 'ascend' ? 'ASC' : 'DESC');
            }, function ($query) {
                return $query->latest('id');
            })
            ->when($name, function ($query, $name) {
                return $query->where('name', 'like', "%{$name}%");
            })
            ->when($regions, function ($query, $regions) {
                if (isset($regions[0])) {
                    $query->where('province_id', (int) $regions[0]);
                }

                if (isset($regions[1])) {
                    $query->where('city_id', (int) $regions[1]);
                }

                if (isset($regions[2])) {
                    $query->where('dist_id', (int) $regions[2]);
                }

                return $query;
            })
            ->paginate(50);

        return successResponse($vendors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = e($request->get('name', ''));
        if (empty($name)) {
            return errorResponse(400, '供应商名称必填');
        }

        $regions = $request->get('regions', []);
        if (empty($regions) || !is_array($regions)) {
            return errorResponse(400, '地区未选择');
        }

        $province = $city = $dist = null;
        if (isset($regions[0])) {
            $province = Region::province()->find($regions[0]);
        }

        if (isset($regions[1])) {
            $city = Region::city()->find($regions[1]);
        }

        if (isset($regions[2])) {
            $dist = Region::dist()->find($regions[2]);
        }

        $address = e($request->get('address', ''));
        if (empty($address)) {
            return errorResponse(400, '详细地址必填');
        }

        $contact = e($request->get('contact', ''));
        if (empty($contact)) {
            return errorResponse(400, '联系人必填');
        }

        $tel = $request->get('tel', '');
        if (!isValidPhone($tel)) {
            return errorResponse(400, '联系电话有误');
        }

        $vendor              = new Vendor;
        $vendor->name        = $name;
        $vendor->province_id = $province ? $province->id : null;
        $vendor->city_id     = $city ? $city->id : null;
        $vendor->dist_id     = $dist ? $dist->id : null;
        $vendor->address     = $address;
        $vendor->contact     = $contact;
        $vendor->tel         = $tel;
        $vendor->save();

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
        //
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
        $vendor = Vendor::find($id);

        if (!$vendor) {
            return errorResponse(404);
        }

        $name = e($request->get('name', ''));
        if (empty($name)) {
            return errorResponse(400, '供应商名称必填');
        }

        $address = e($request->get('address', ''));
        if (empty($address)) {
            return errorResponse(400, '详细地址必填');
        }

        $contact = e($request->get('contact', ''));
        if (empty($contact)) {
            return errorResponse(400, '联系人必填');
        }

        $tel = $request->get('tel', '');
        if (!isValidPhone($tel)) {
            return errorResponse(400, '联系电话有误');
        }

        $vendor->name    = $name;
        $vendor->address = $address;
        $vendor->contact = $contact;
        $vendor->tel     = $tel;
        $vendor->save();

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
        //
    }
}
