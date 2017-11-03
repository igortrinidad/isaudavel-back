<?php

namespace App\Http\Controllers;

use App\Events\CompanyNotification;
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
        $rating = tap( CompanyRating::create($request->all()))->fresh(['client', 'company']);

        //Notify the company
        event( new CompanyNotification($rating->company_id, ['type' => 'new_rating', 'payload' => $rating]));

        return response()->json([
            'message' => 'Company rating created.',
            'rating' => $rating,
            'company' => $rating->company
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

    /**
     *  Check Rating
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    public function checkRating(Request $request)
    {
        $already_rated = false;

        $check_rating = CompanyRating::where('client_id', \Auth::user()->id)
            ->whereHas('company', function ($query) use ($request){
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
