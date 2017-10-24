<?php

namespace App\Http\Controllers;

use App\Models\ProfessionalRating;
use Illuminate\Http\Request;

class ProfessionalRatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $ratings = ProfessionalRating::where('professional_id', $id)
            ->with('client')
            ->orderBy('created_at')
            ->get();

        return response()->json(['ratings' => $ratings]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rating = ProfessionalRating::create($request->all());

        return response()->json([
            'message' => 'Professional rating created.',
            'rating' => $rating->fresh(['client']),
            'professional' => $rating->professional->makeHidden(['companies','categories','blank_password'])
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
        $rating = ProfessionalRating::find($id);

        return response()->json(['data' => $rating]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rating = tap(ProfessionalRating::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Professional rating updated.',
            'rating' => $rating
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
        $destroyed = ProfessionalRating::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Professional rating destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Professional rating not found.',
        ], 404);

    }

    /**
     *  Check Rating
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    public function checkRating(Request $request)
    {
        $already_rated = false;

        $check_rating = ProfessionalRating::where('client_id', \Auth::user()->id)
            ->whereHas('professional', function ($query) use ($request){
                $query->where('slug', $request->get('slug'));
            })->first();

        if($check_rating){
            $already_rated = true;
        }

        return response()->json([
            'already_rated' => $already_rated,
        ]);
    }
}
