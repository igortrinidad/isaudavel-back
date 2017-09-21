<?php

namespace App\Http\Controllers;

use App\Models\MealRecipePhoto;
use Illuminate\Http\Request;

class MealRecipePhotoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $is_cover = $request->get('is_cover') == 'true' ? true : false;

        $image = $request->file('file');

        $fileName = bin2hex(random_bytes(16)) . '.' . $image->getClientOriginalExtension();

        $filePath = 'meal_recipes/photo/' . $fileName;

        \Storage::disk('media')->put($filePath, file_get_contents($image), 'public');

        $request->merge(['path' => $filePath, 'is_cover' => $is_cover]);

        $photo = MealRecipePhoto::create($request->all());

        $response = [
            'message' => 'Meal recipe photo created.',
            'photo'    => $photo->fresh()->toArray(),
        ];

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $photo = tap(MealRecipePhoto::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Meal recipe photo updated.',
            'photo' => $photo
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $photo = MealRecipePhoto::find($id);

        \Storage::disk('media')->delete($photo->path);

        $destroyed = $photo->delete();

        if($destroyed){
            return response()->json([
                'message' => 'Meal recipe photo destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Meal recipe photo not found.',
        ], 404);
    }
}
