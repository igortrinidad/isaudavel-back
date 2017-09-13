<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Trainning;
use Illuminate\Http\Request;

class TrainningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $trainnings = Trainning::where('client_id', $request->get('client_id'))->with('from')->orderBy('updated_at', 'DESC')->get();

        foreach($trainnings as $trainning){
           if($trainning->from->role == 'professional'){
              $trainning->from->load('categories');
           }
        }

        return response()->json(['trainnings' => $trainnings]);
    }

    /**
     * Display a listing of the resource destroyeds.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function listdestroyeds(Request $request)
    {
        $trainnings = Trainning::where('client_id', $request->get('client_id'))->with('from')->onlyTrashed()->get();

        return response()->json(['trainnings_destroyeds' => $trainnings]);
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

        $trainning = Trainning::create($request->all());

        //Atividade
        if($request->get('share_profile')){
            Activity::create([
                'client_id' => $request->get('client_id'),
                'content' => 'Adicionou uma ficha de treinamento',
                'created_by_id' => \Auth::user()->id,
                'created_by_type' => get_class(\Auth::user()),
                'about_id' => $trainning->id,
                'about_type' => get_class($trainning),
                'is_public' => 1,
                'xp_earned' => 50,
            ]);
        }

        return response()->json([
            'message' => 'Trainning created.',
            'trainning' => $trainning->fresh(['from'])
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
        $trainning = Trainning::find($id);

        return response()->json(['data' => $trainning]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $trainning = tap(Trainning::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Trainning updated.',
            'trainning' => $trainning
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
        $destroyed = Trainning::destroy($request->get('trainning_id'));

        //Activity::where('about_id', $request->get('trainning_id'))->delete();

        if($destroyed){
            return response()->json([
                'message' => 'Trainning destroyed.',
                'id' => $request->get('trainning_id')
            ]);
        }

        return response()->json([
            'message' => 'Trainning not found.',
        ], 404);

    }

    public function undestroy(Request $request)
    {
        $undestroyed = Trainning::withTrashed()
        ->where('id', $request->get('trainning_id'))
        ->restore();

        if($undestroyed){
            return response()->json([
                'message' => 'trainning undestroyed.',
                'id' => $request->get('trainning_id')
            ]);
        }

        return response()->json([
            'message' => 'trainning not found.',
        ], 404);

    }
}
