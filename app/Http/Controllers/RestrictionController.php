<?php

namespace App\Http\Controllers;

use App\Models\Restriction;
use Illuminate\Http\Request;

class RestrictionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $restrictions = Restriction::where('client_id', $id)
            ->with('from')
            ->orderBy('created_at')
            ->get();

        return response()->json(['restrictions' => $restrictions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $restriction = Restriction::create($request->all());

        return response()->json([
            'message' => 'Restriction created.',
            'restriction' => $restriction->fresh(['from'])
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $restriction = Restriction::find($id);

        return response()->json(['data' => $restriction]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $restriction = tap(Restriction::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Restriction updated.',
            'restriction' => $restriction
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroyed = Restriction::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Restriction destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Restriction not found.',
        ], 404);

    }
}
