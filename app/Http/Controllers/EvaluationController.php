<?php

namespace App\Http\Controllers;

use App\Events\ClientActivity;
use App\Models\Activity;
use App\Models\Evaluation;
use App\Models\EvaluationPhoto;
use Illuminate\Http\Request;
use PDF;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $evaluations = Evaluation::where('client_id', $request->get('client_id'))
            ->with(['from', 'photos'])
            ->orderBy('created_at')
            ->get();

        return response()->json(['evaluations' => $evaluations]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->merge([
            'created_by_id' => \Auth::user()->id,
            'created_by_type' => get_class(\Auth::user())
        ]);

        $evaluation = Evaluation::create($request->all());

        //Xp points
        event(new ClientActivity($evaluation->client, 1));

        //Adiciona atividade
        if($request->get('share_profile')){
            Activity::create([
                'client_id' => $request->get('client_id'),
                'content' => 'Adicionou uma avaliação',
                'created_by_id' => \Auth::user()->id,
                'created_by_type' => get_class(\Auth::user()),
                'about_id' => $evaluation->id,
                'about_type' => get_class($evaluation),
                'is_public' => 1,
                'xp_earned' => 50,
            ]);
        }

        //update photos
        if (array_key_exists('photos', $request->all())) {
            foreach ($request->get('photos') as $photo) {
                EvaluationPhoto::find($photo['id'])->update($photo);
            }
        }

        return response()->json([
            'message' => 'evaluation created.',
            'evaluation' => $evaluation->fresh(['from'])
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
        $evaluation = Evaluation::find($id);

        return response()->json(['data' => $evaluation]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $evaluation = tap(Evaluation::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Evaluation updated.',
            'evaluation' => $evaluation
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
        $destroyed = Evaluation::destroy($request->get('evaluation_id'));

        if($destroyed){
            return response()->json([
                'message' => 'Evaluation destroyed.',
                'id' => $request->get('evaluation_id')
            ]);
        }

        return response()->json([
            'message' => 'Evaluation not found.',
        ], 404);

    }

    /**
     * Display a listing of the resource destroyeds.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function listdestroyeds(Request $request)
    {
        $evaluations = Evaluation::where('client_id', $request->get('client_id') )->with(['from', 'photos'])->onlyTrashed()->get();

        return response()->json(['evaluations_destroyeds' => $evaluations]);
    }

    /**
     * Restore a evaluation.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function undestroy(Request $request)
    {
        $undestroyed = Evaluation::withTrashed()
        ->where('id', $request->get('evaluation_id'))
        ->restore();

        if($undestroyed){
            return response()->json([
                'message' => 'Evaluation undestroyed.',
                'id' => $request->get('evaluation_id')
            ]);
        }

        return response()->json([
            'message' => 'Evaluation not found.',
        ], 404);

    }

    /**
     * history of indexs.
     *
     * @param $index - the label type
     * @return \Illuminate\Http\Response
     */
    public function indexHistory(Request $request)
    {
        $query = '"label": "' .$request->get('index'). '"'; 

        $evaluations = Evaluation::where('client_id', $request->get('client_id'))
        ->where('items', 'like', '%' . $request->get('index') . '%')
        ->orderBy('created_at', 'ASC')
        ->get();

        $data = new Class{};
        $data->labels = [];
        $data->value = [];
        $data->target = [];

        foreach($evaluations as $eval){
            array_push($data->labels, $eval->created_at);

            foreach($eval->items as $item){
                if($item['label'] == $request->get('index')){
                    array_push($data->value, $item['value']);
                    array_push($data->target, $item['target']);
                }
            }

        }

        return response()->json(['evaluations' => $data]);

    }

    public function generate_pdf($id){

        $evaluation = Evaluation::where('id', $id)->with(['from', 'client'])->first();

        if(!$evaluation){
            abort(404);   
        }

        $data = [
            'evaluation' => $evaluation
        ];

        $pdf = PDF::loadView('pdf.evaluation', $data, [
            'orientation' => 'P'
        ]);

        return $pdf->stream('App_iSaudavel_Avaliacao_' . $evaluation->id . '.pdf');

    }
}
