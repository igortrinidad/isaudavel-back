<?php

namespace App\Http\Controllers;

use App\Models\Activity;
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
    public function index(Request $request)
    {
        $restrictions = Restriction::where('client_id', $request->get('client_id'))->with('from')->orderBy('updated_at', 'DESC')->get();

        return response()->json(['restrictions' => $restrictions]);
    }

    /**
     * Display a listing of the resource destroyeds.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function listdestroyeds(Request $request)
    {
        $restrictions = Restriction::where('client_id', $request->get('client_id'))->with('from.categories')->onlyTrashed()->get();

        return response()->json(['restrictions_destroyeds' => $restrictions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->merge(['created_by_id' => \Auth::user()->id, 'created_by_type' => get_class(\Auth::user())]);

        $restriction = Restriction::create($request->all());

        //Adiciona atividade
        if($request->get('share_profile')){
            Activity::create([
                'client_id' => $request->get('client_id'),
                'content' => 'Adicionou uma restrição',
                'created_by_id' => \Auth::user()->id,
                'created_by_type' => get_class(\Auth::user()),
                'about_id' => $restriction->id,
                'about_type' => get_class($restriction),
                'is_public' => 1,
                'xp_earned' => 50,
            ]);
        }

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
    public function show(Request $request)
    {
        $restriction = Restriction::find($request->get('client_id'));

        return response()->json(['restriction' => $restriction]);
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
    public function destroy(Request $request)
    {
        $destroyed = Restriction::destroy($request->get('restriction_id'));

        if($destroyed){
            return response()->json([
                'message' => 'Restriction destroyed.',
                'id' => $request->get('restriction_id')
            ]);
        }

        return response()->json([
            'message' => 'Restriction not found.',
        ], 404);

    }

    public function undestroy(Request $request)
    {
        $undestroyed = Restriction::withTrashed()
        ->where('id', $request->get('restriction_id'))
        ->restore();

        if($undestroyed){
            return response()->json([
                'message' => 'Restriction undestroyed.',
                'id' => $request->get('restriction_id')
            ]);
        }

        return response()->json([
            'message' => 'Restriction not found.',
        ], 404);

    }
}
