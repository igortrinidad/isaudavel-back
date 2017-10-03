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
       /* $events = Event::with(['from', 'photos', 'modality', 'submodalities'])->paginate(12);*/

        $latitude = $request->has('latitude') ? $request->get('latitude') :  null;
        $longitude = $request->has('longitude') ? $request->get('longitude') :  null;

        $events = Event::select(\DB::raw("*, 
                (ATAN(SQRT(POW(COS(RADIANS(events.lat)) * SIN(RADIANS(events.lng)
                 - RADIANS('$longitude')), 2) +POW(COS(RADIANS('$latitude')) * 
                 SIN(RADIANS(events.lat)) - SIN(RADIANS('$latitude')) * cos(RADIANS(events.lat)) * 
                 cos(RADIANS(events.lng) - RADIANS('$longitude')), 2)),SIN(RADIANS('$latitude')) * 
                 SIN(RADIANS(events.lat)) + COS(RADIANS('$latitude')) * COS(RADIANS(events.lat)) * 
                 COS(RADIANS(events.lng) - RADIANS('$longitude'))) * 6371000) as distance_m"))
            ->whereHas('modality', function ($query) use ($request) {
                if(!empty($request->get('modalities'))) {
                    $query->whereIn('slug', $request->get('modalities'));
                }
            })->whereHas('submodalities', function ($query) use ($request) {
                if(!empty($request->get('submodalities'))) {
                    $query->whereIn('slug', $request->get('submodalities'));
                }
            })
            ->where(function($query) use($request){
                if(!empty($request->get('search'))){
                    $search = explode(' ', $request->get('search'));
                    $query->where('name', 'LIKE', '%' . $request->get('search'). '%');
                    $query->orWhereIn('name', $search);
                }
            })
            ->with(['from', 'photos', 'modality', 'submodalities'])
            ->orderBy('distance_m', 'asc')
            ->paginate(12);

        return response()->json(custom_paginator($events, 'events'));
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

        $events = Event::with('from', 'photos', 'modality', 'submodalities')
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

        // sub modalities
        $event->submodalities()->attach($request->get('submodalities'));

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
        $event = Event::with(['photos', 'categories', 'from', 'comments', 'modality', 'submodalities'])->find($id);

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

        // sub modalities
        $event->submodalities()->sync($request->get('submodalities'));

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
