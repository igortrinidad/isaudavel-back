<?php

namespace App\Http\Controllers;

use App\Models\MealRecipeComment;
use Illuminate\Http\Request;

class MealRecipeCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $comments = MealRecipeComment::where('meal_recipe_id', $id)->with(['from'])->orderBy('created_at', 'desc')->paginate(10);

        return response()->json(custom_paginator($comments, 'comments'));
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

        $comment = MealRecipeComment::create($request->all());

        //Send Mail
        $data = [];
        $data['align'] = 'center';

        $data['messageTitle'] = '<h4>Novo comentário</h4>';
        $data['messageOne'] = 'Sua receita '. $comment->meal_recipe->title .' tem um novo comentário:';
        $data['messageTwo'] = '<strong>Usuário:</strong> '.$comment->meal_recipe->from->full_name. '<br><strong>Comentário:</strong> '.$comment->content;
        $data['messageThree'] = 'Acesse online em https://isaudavel.com ou baixe o aplicativo para Android e iOS (Apple)';

        $data['messageSubject'] = 'Novo comentário';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data, $comment){
            $message->from('no-reply@isaudavel.com', 'iSaudavel App');
            $message->to($comment->meal_recipe->from->email, $comment->meal_recipe->from->full_name)->subject($data['messageSubject']);
        });

        return response()->json([
            'message' => 'Comment created.',
            'comment' => $comment->fresh('from')
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
        $comment = MealRecipeComment::find($id);

        return response()->json(['data' => $comment]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $comment = tap(MealRecipeComment::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Comment updated.',
            'comment' => $comment
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
        $destroyed = MealRecipeComment::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Comment destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Comment not found.',
        ], 404);

    }
}
