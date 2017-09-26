<?php

namespace App\Http\Controllers;


use App\Models\MealRecipeRating;
use Illuminate\Http\Request;

class MealRecipeRatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $ratings = MealRecipeRating::where('meal_recipe_id', $id)->with(['from'])->orderBy('created_at', 'desc')->paginate(10);

        return response()->json(custom_paginator($ratings, 'ratings'));
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

        $rating = MealRecipeRating::create($request->all());

        //Send Mail
        $data = [];
        $data['align'] = 'center';

        $data['messageTitle'] = '<h4>Nova avaliação</h4>';
        $data['messageOne'] = 'Sua receita '. $rating->meal_recipe->title .' tem uma nova avaliação:';
        $data['messageTwo'] = '<strong>Usuário:</strong> '.$rating->meal_recipe->from->full_name. '<br><strong>Avaliação:</strong> '.$rating->rating;
        $data['messageThree'] = 'Acesse online em https://isaudavel.com ou baixe o aplicativo para Android e iOS (Apple)';

        $data['messageSubject'] = 'Nova avaliação';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data, $rating){
            $message->from('no-reply@isaudavel.com', 'iSaudavel App');
            $message->to($rating->meal_recipe->from->email, $rating->meal_recipe->from->full_name)->subject($data['messageSubject']);
        });

        return response()->json([
            'message' => 'Rating created.',
            'comment' => $rating->fresh('from')
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
        $rating = MealRecipeRating::find($id);

        return response()->json(['data' => $rating]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroyed = MealRecipeRating::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Rating destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Rating not found.',
        ], 404);

    }
}
