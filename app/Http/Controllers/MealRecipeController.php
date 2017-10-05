<?php

namespace App\Http\Controllers;

use App\Events\ClientActivity;
use App\Models\MealRecipe;
use App\Models\MealRecipePhoto;
use Illuminate\Http\Request;
use PDF;

class MealRecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $meal_recipes = MealRecipe::whereHas('types', function($query) use ($request){
            if(!empty($request->get('types'))){
                $query->whereIn('slug', $request->get('types'));
            }
        })
        ->orWhereHas('tags', function ($query) use ($request) {
            if(!empty($request->get('tags'))) {
                $query->whereIn('slug', $request->get('tags'));
            }

        })->where(function($query) use($request){
            foreach($request->get('nutrients') as $key => $value){
                if(!$value){
                    continue;
                }
                // - or + 20%
                $min = $value - ( $value * 20 / 100);
                $max = $value + ( $value * 20 / 100);

                $query->orWhereBetween($key,[$min, $max]);
            }
        })->where(function($query) use($request){

            if($request->has('search') && !empty($request->get('search'))){
                $search = explode(' ', $request->get('search'));
                $query->where('title', 'LIKE', '%' . $request->get('search') . '%');
                $query->orWhereIn('title', $search);
            }
        })
            ->with(['tags' => function($query){
            $query->select('id', 'name', 'slug');
        }, 'type', 'types'])->paginate(12);

        return response()->json(custom_paginator($meal_recipes));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function homeList(Request $request)
    {
        $limit = $request->get('limit') ? $request->get('limit') : 8;

        $meal_recipes = MealRecipe::with('type', 'tags', 'types')
           ->orderByRaw("RAND()")
            ->limit($limit)
            ->get();

        return response()->json(['meal_recipes' => $meal_recipes]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function recipesByUser($id)
    {
        $meal_recipes = MealRecipe::where('created_by_id', $id)->with('from')->paginate(10);

        return response()->json(custom_paginator($meal_recipes, 'meal_recipes'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function searchByType(Request $request)
    {
        $meal_recipes = MealRecipe::whereHas('type', function($query) use ($request){
            $query->where('slug', $request->get('slug'));
        })->with(['tags' => function($query){
            $query->select('id', 'name', 'slug');
        }, 'comments', 'type', 'types'])->get();

        return response()->json(['count' => $meal_recipes->count(), 'data' => $meal_recipes]);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function searchByTag(Request $request)
    {
        $meal_recipes = MealRecipe::where('type_id', $request->get('type_id'))
            ->whereHas('tags', function ($query) use ($request) {
                $query->whereIn('slug', $request->get('tags'));

            })->with(['tags', 'comments', 'type', 'types'])->get();

        return response()->json(['count' => $meal_recipes->count(), 'data' => $meal_recipes]);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function searchByTitle(Request $request)
    {
        $search = explode(' ', $request->get('search'));

        $meal_recipes = MealRecipe::where(function($query) use($request, $search){
            $query->where('title', 'LIKE', '%' . $request->get('search') . '%');
            $query->orWhereIn('title', $search);

        })->with(['tags', 'type', 'types'])->paginate(1);

        return response()->json(custom_paginator($meal_recipes));
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function filterNutrients(Request $request)
    {
        $nutrients = $request->get('nutrients');

        $meal_recipes = MealRecipe::where('type_id', $request->get('type_id'))
            ->where(function($query) use($nutrients){
            foreach($nutrients as $key => $value){
                if(!$value){
                    continue;
                }
                // - or + 20%
                $min = $value - ( $value * 20 / 100);
                $max = $value + ( $value * 20 / 100);

                $query->orWhereBetween($key,[$min, $max]);
            }

        })->with('type','from', 'types')->get();

        return response()->json(['count' => $meal_recipes->count(), 'data' => $meal_recipes]);
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

        //update photos
        if (array_key_exists('photos', $request->all())) {
            foreach ($request->get('photos') as $photo) {
                MealRecipePhoto::find($photo['id'])->update($photo);
            }
        }

        $meal_recipe = MealRecipe::create($request->all());

        //Xp points
        if(\Auth::user()->role == 'client'){
            event(new ClientActivity(\Auth::user(), 5));
        }

        //Attach Types
        if($request->has('types') && !empty($request->get('types'))){
            $meal_recipe->types()->attach($request->get('types'));
        }

        //Attach tags
        if($request->has('tags') && !empty($request->get('tags'))){
            $meal_recipe->tags()->attach($request->get('tags'));
        }

        return response()->json([
            'message' => 'Meal type created.',
            'meal_recipe' => $meal_recipe->fresh()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $meal_recipe = MealRecipe::with('photos', 'tags', 'type', 'types')->find($id);

        return response()->json(['meal_recipe' => $meal_recipe]);
    }

    /**
     * Display the specified resource.
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function showPublic($slug)
    {
        $meal_recipe = MealRecipe::where('slug', $slug)->with('photos', 'from', 'tags', 'types')->first();

        return response()->json(['data' => $meal_recipe]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //update photos
        if (array_key_exists('photos', $request->all())) {
            foreach ($request->get('photos') as $photo) {
                MealRecipePhoto::find($photo['id'])->update($photo);
            }
        }

        $meal_recipe = tap(MealRecipe::find($request->get('id')))->update($request->all())->fresh();

        //Sync types
        if($request->has('types') && !empty($request->get('types'))){
            $meal_recipe->types()->sync($request->get('types'));
        }

        //Sync tags
        if($request->has('tags') && !empty($request->get('tags'))){
            $meal_recipe->tags()->sync($request->get('tags'));
        }

        return response()->json([
            'message' => 'Meal recipe updated.',
            'meal_recipe' => $meal_recipe
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $recipe = MealRecipe::find($request->get('recipe_id'));

        if($recipe){
            //Remove relations
            $recipe->comments()->delete();

            foreach ($recipe->photos as $photo) {
                \Storage::disk('media')->delete($photo->path);
                $photo->delete();
            }

            $recipe->types()->detach();
            $recipe->tags()->detach();


            if($recipe->delete()){
                return response()->json([
                    'message' => 'Meal recipe destroyed.',
                ]);
            }
        }
        return response()->json([
            'message' => 'Meal recipe not found.',
        ], 404);

    }

    public function generate_pdf($id){

        $meal = MealRecipe::where('id', $id)->with('from')->first();

        $data = [
            'meal' => $meal
        ];

        $pdf = PDF::loadView('pdf.recipe', $data);
        return $pdf->stream('iSaudavel_' . $meal->slug . '.pdf');

    }
}
