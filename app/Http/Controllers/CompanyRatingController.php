<?php

namespace App\Http\Controllers;

use App\Models\CompanyRating;
use Illuminate\Http\Request;

class CompanyRatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $ratings = CompanyRating::where('company_id', $id)
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
        $rating = CompanyRating::create($request->all());

        return response()->json([
            'message' => 'Company rating created.',
            'rating' => $rating->fresh()
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
        $rating = CompanyRating::find($id);

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
        $rating = tap(CompanyRating::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Company rating updated.',
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
        $destroyed = CompanyRating::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Company rating destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Company rating not found.',
        ], 404);

    }
}
