<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Professional;
use App\Models\Recomendation;
use Illuminate\Http\Request;

class RecomendationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function receivedList($id)
    {
        $recomendations = Recomendation::where('to_id', $id)
            ->with('from')
            ->orderBy('created_at')
            ->get();

        return response()->json(['recomendations' => $recomendations]);
    }
    /**
         * Display a listing of the resource.
         *
         * @param $id
         * @return \Illuminate\Http\Response
         */
        public function sentList($id)
        {
            $recomendations = Recomendation::where('from_id', $id)
                ->with('to')
                ->orderBy('created_at')
                ->get();

            return response()->json(['recomendations' => $recomendations]);
        }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $to_type = $request->get('to_type') == 'professional' ? Professional::class : Company::class;

        $request->merge(['to_type' => $to_type, 'from_id' => \Auth::user()->id, 'from_type' => get_class(\Auth::user())]);

        $recomendation = Recomendation::create($request->all());

        return response()->json([
            'message' => 'Recomendation created.',
            'recomendation' => $recomendation->fresh(['from']),
            'from' => $recomendation->from->setHidden(['companies','categories','blank_password'])
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
        $recomendation = Recomendation::find($id);

        return response()->json(['data' => $recomendation]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $recomendation = tap(Recomendation::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Recomendation updated.',
            'recomendation' => $recomendation,
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
        $destroyed = Recomendation::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Recomendation destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Recomendation not found.',
        ], 404);

    }
}
