<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Event;
use App\Models\EventPhoto;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $events = Event::with(['from', 'categories', 'photos'])->get();

        return response()->json(['events' => $events]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function homeList(Request $request)
    {
        $limit = $request->get('limit') ? $request->get('limit') : 8;

        $events = Event::with('from', 'categories', 'photos')
            ->orderByRaw("RAND()")
            ->limit($limit)
            ->get();

        return response()->json(['events' => $events]);
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

        $event = Event::create($request->all());

        // attach categories
        $event->categories()->attach($request->get('categories'));

        //update photos
        if (array_key_exists('photos', $request->all())) {
            foreach ($request->get('photos') as $photo) {
                EventPhoto::find($photo['id'])->update($photo);
            }
        }

        return response()->json([
            'message' => 'event created.',
            'event' => $event->fresh(['from'])
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
        $event = Event::with(['photos', 'categories', 'from', 'comments'])->find($id);

        return response()->json(['event' => $event]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        if(\Auth::user()->id != $request->get('created_by_id')){
            return response()->json(['error' => 'Forbiden.'], 403);
        }

        $event = tap(Event::find($request->get('id')))->update($request->all())->fresh();

        // attach categories
        $event->categories()->detach();
        $event->categories()->attach($request->get('categories'));

        //update photos
        if (array_key_exists('photos', $request->all())) {
            foreach ($request->get('photos') as $photo) {
                EventPhoto::find($photo['id'])->update($photo);
            }
        }

        return response()->json([
            'message' => 'event created.',
            'event' => $event->fresh(['from'])
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
        $destroyed = Event::destroy($request->get('event_id'));

        if($destroyed){
            return response()->json([
                'message' => 'event destroyed.',
                'id' => $request->get('event_id')
            ]);
        }

        return response()->json([
            'message' => 'event not found.',
        ], 404);

    }

    /**
     *  Check Slug
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    public function check_slug($slug)
    {
        $event = Event::where('slug', $slug)->first();

        if($event){
            $already_exist = true;
        } else {
            $already_exist = false;
        }

        return response()->json([
            'already_exist' => $already_exist,
        ], 200);
    }

}
