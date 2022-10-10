<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Exception;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parentId = (int) $request->get('parent_id', Region::CHINA);

        $region = Region::find($parentId);

        if (!$region) {
            return successResponse();
        }

        switch ($region) {
            case $region->level == Region::LEVEL_COUNTRY:
                $items = Region::provinces();
                break;
            case $region->level == Region::LEVEL_PROVINCE:
                $items = Region::cities($region);
                break;
            case $region->level == Region::LEVEL_CITY:
                $items = Region::dists($region);
                break;
            default:
                $items = [];
                break;
        }

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
        //
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
            $region = Region::findOrFail($id);

            return successResponse($region);
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
        //
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
