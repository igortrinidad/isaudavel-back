<?php

namespace App\Http\Controllers;

use App\Models\Professional;
use Illuminate\Http\Request;


class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        return view('oracle.dashboard.sales.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboardData()
    {
        //Alguns metodos nao existem no pacote hubspot-laravel, entao criei esse metodo para facilitar

        $engagementsEndpoint = 'https://api.hubapi.com/engagements/v1/engagements/recent/modified';
        $owners = \HubSpot::owners()->all()->data;
        //dd($owners);

        $last_engagements = hubspot_support($engagementsEndpoint, ['count' => 5])->data->results;

        foreach ($last_engagements as $engagement) {


            $owner_name = '';

            foreach ($owners as $owner) {
                if ($owner->ownerId == $engagement->engagement->ownerId) {
                    $owner_name = $owner->firstName . ' ' . $owner->lastName;

                }

                $engagement->metadata->username = $owner_name;



                $engagement->engagement->created_at = date('d/m/Y H:i:s', $engagement->engagement->createdAt/1000);

                if(isset($engagement->metadata->body)){
                    $engagement->metadata->body = str_limit(strip_tags($engagement->metadata->body), 100);
                }

            }
        }

        $professionals = Professional::all()->count();

        return response()->json(['professionals_count' => $professionals, 'last_engagements' => $last_engagements]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
