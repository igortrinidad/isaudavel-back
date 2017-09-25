<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientPhoto;
use Carbon\Carbon;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use DB;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::orderBy('name', 'asc')->paginate(10);

        return response()->json(custom_paginator($clients));
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function companyClients(Request $request)
    {
        $clients_confirmed = Client::whereHas('companies', function ($query) use($request){
            $query->where('company_id', $request->get('company_id'))
                ->where('is_confirmed', true)
                ->where('is_deleted', false);
        })->with(['companies' => function($query) use($request){
            $query->where('company_id', $request->get('company_id'));
        }])->orderBy('name')->paginate(10);

        $clients_unconfirmed = Client::whereHas('companies', function ($query) use($request){
            $query->where('company_id', $request->get('company_id'))
                ->where('is_confirmed', false)
                ->where('is_deleted', false);
        })->with(['companies' => function($query) use($request){
            $query->where('company_id', $request->get('company_id'));
        }])->orderBy('name')->paginate(10);


        $clients_deleted = Client::whereHas('companies', function ($query) use($request){
            $query->where('company_id', $request->get('company_id'))
                ->where('is_confirmed', false)
                ->where('is_deleted', true);
        })->with(['companies' => function($query) use($request){
            $query->where('company_id', $request->get('company_id'))->withPivot('deleted_by_id');
        }])->orderBy('name')->paginate(10);


        return response()->json([
            'clients_confirmed' => custom_paginator($clients_confirmed, 'clients_confirmed'),
            'clients_unconfirmed' => custom_paginator($clients_unconfirmed, 'clients_unconfirmed'),
            'clients_deleted' => custom_paginator($clients_deleted, 'clients_deleted'),
        ]);
    }

    /**
     * Client Search.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $search = explode(' ', $request->get('search'));

        $clients_confirmed = Client::whereHas('companies', function ($query) use($request){
            $query->where('company_id', $request->get('company_id'))
                ->where('is_confirmed', true)
                ->where('is_deleted', false);
        })->where(function($query) use($request, $search){
            $query->where('name', 'LIKE', '%' . $request->get('search') . '%');
            $query->orWhere('last_name', 'LIKE', '%' . $request->get('search') . '%');
            $query->orWhere('email', 'LIKE', '%' . $request->get('search') . '%');

            //for full name
            $query->orWhereIn('name', $search);
            $query->orWhere(function ($query) use ($search) {
                $query->whereIn('last_name', $search);
            });
        })->with(['companies' => function($query) use($request){
            $query->where('company_id', $request->get('company_id'));
        }])->orderBy('name')->paginate(10);

        $clients_unconfirmed = Client::whereHas('companies', function ($query) use($request){
            $query->where('company_id', $request->get('company_id'))
                ->where('is_confirmed', false)
                ->where('is_deleted', false);
        })->where(function($query) use($request, $search){
            $query->where('name', 'LIKE', '%' . $request->get('search') . '%');
            $query->orWhere('last_name', 'LIKE', '%' . $request->get('search') . '%');
            $query->orWhere('email', 'LIKE', '%' . $request->get('search') . '%');

            //for full name
            $query->orWhereIn('name', $search);
            $query->orWhere(function ($query) use ($search) {
                $query->whereIn('last_name', $search);
            });
        })->with(['companies' => function($query) use($request){
            $query->where('company_id', $request->get('company_id'));
        }])->orderBy('name')->paginate(10);


        $clients_deleted = Client::whereHas('companies', function ($query) use($request){
            $query->where('company_id', $request->get('company_id'))
                ->where('is_confirmed', false)
                ->where('is_deleted', true);
        })->with(['companies' => function($query) use($request){
            $query->where('company_id', $request->get('company_id'));
        }])->orderBy('name')->paginate(10);

        //Quando não clientes deixar a company procurar somente de posse do email para não virar spam ou uma company que não tem acesso mesmo ao cliente ficar solicitando para ver as infos...
        $non_clients = Client::whereDoesntHave('companies', function ($query) use($request, $search){
            $query->where('company_id', $request->get('company_id'));
        })->where(function($query) use($request, $search){
            $query->orWhere('email', $request->get('search'));
        })->orderBy('name')->paginate(10);


        return response()->json([
            'clients_confirmed' => custom_paginator($clients_confirmed, 'clients_confirmed'),
            'clients_unconfirmed' => custom_paginator($clients_unconfirmed, 'clients_unconfirmed'),
            'clients_deleted' => custom_paginator($clients_deleted, 'clients_deleted'),
            'non_clients' => custom_paginator($non_clients, 'non_clients'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //see cause this dont work inside model create

        $checkClient = Client::where('email', $request->get('email'))->first();

        if($checkClient){
            return response()->json([
                'message' => 'Client already exist.',
                'status' => 422
            ]);
        }

        $request['bday'] = Carbon::createFromFormat('d/m/Y', $request['bday'])->toDateString();


        if($request->has('password') && $request->get('password')){
            $pass = $request->get('password');
        } else {
            $pass = rand(100000, 999999);
        }
        
        $request->merge([
            'password' => bcrypt($pass),
            'remember_token' => str_random(10)
        ]);


        $client = Client::create($request->all());

        //If is a company creating a client attach automatically
        if($request->has('company_id') && $request->get('company_id')){
            $client->companies()->attach($request->get('company_id'),
                [
                    'is_confirmed' => true,
                    'confirmed_by_id' => \Auth::user()->id,
                    'confirmed_by_type' => get_class(\Auth::user()),
                    'confirmed_at' => Carbon::now(),
                    'trainnings_show' => true,
                    'trainnings_edit' => true,
                    'diets_show' => true,
                    'diets_edit' => true,
                    'evaluations_show' => true,
                    'evaluations_edit' => true,
                    'restrictions_show' => true,
                    'restrictions_edit' => true,
                    'exams_show' => true,
                    'exams_edit' => true,
                ]);

            //Envia email para informar o cliente do cadastro
            $data = [];
            $data['align'] = 'center';
            $data['messageTitle'] = '<h4>Cadastro iSaudavel</h4>';
            $data['messageOne'] = '
            <p>Olá ' . $request->get('name') . ',</p>
            <p>O profissional <b>' . \Auth::user()->full_name . '</b> acabou de criar um perfil para você na plataforma <a href="https://isaudavel.com" target="_blank">iSaudavel</a>. Agora você poderá acessar diretamente suas avaliações físicas, dietas, fichas de treinamento além de controlar seus agendamentos com as empresas que você adicionar e muito mais.
            </p>
            <br>
            <p>Acesse online em <a href="https://app.isaudavel.com">app.isaudavel.com</a> ou baixe o aplicativo 
            para <a href="https://play.google.com/store/apps/details?id=com.isaudavel" target="_blank">Android</a> e <a href="https://itunes.apple.com/us/app/isaudavel/id1277115133?mt=8" target="_blank">iOS (Apple)</a></p>.
            <hr>
            <h4>Dados de acesso</h4>
            <b>
            <h5>Email de acesso</h5>
            <p><b>' .$request->get('email') . '</b></p>
            <h5>Password provisório</h5>
            <p>' . $pass . '</p>
            <p>Solicitamos que você altere sua senha no primeiro acesso.</p>';

            $data['messageSubject'] = 'Cadastro iSaudavel';

            \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data, $request){
                $message->from('no-reply@isaudavel.com', 'iSaudavel App');
                $message->to($request->get('email'), $request->get('name'))->subject($data['messageSubject']);
            });

        }

        return response()->json([
            'message' => 'Client created.',
            'client' => $client->fresh(['photos'])
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {

        $client = Client::find($request->get('client_id'))->load(['photos', 'subscriptions.plan', 'subscriptions.invoices', 'companies']);

        return response()->json(['client' => $client]);
    }

        /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show_public($id)
    {


        $client = Client::where('id', $id)->select('name', 'last_name', 'id', 'total_xp')->first();


        return response()->json(['client' => $client]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function showCompany(Request $request)
    {

        $client = Client::with(['subscriptions' => function($query) use ($request){
            $query->with(['plan', 'invoices.schedules']);
            $query->where('company_id',  '=', $request->get('company_id'));
            $query->orderBy('is_active', 'DESC');
            $query->orderBy('updated_at', 'DESC');
        }])->find($request->get('client_id'));

        return response()->json(['client' => $client]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if($request->has('current_password') && !empty($request->get('current_password'))){

            $user = \Auth::user();

            if(!\Hash::check($request->get('current_password'), $user->password)){

                return response()->json(['error' => true, 'message' => 'Senha atual incorreta'], 200);
            }
        }

        if($request->has('password') && !empty($request['password'])){
            $request->merge([
                'password' => bcrypt($request->get('password')),
            ]);
        }

        //update photos
        if (array_key_exists('photos', $request->all())) {
            foreach ($request->get('photos') as $photo) {
                ClientPhoto::find($photo['id'])->update($photo);
            }
        }

        $client = tap(Client::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Client updated.',
            'client' => $client
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
        $destroyed = Client::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Client destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Client not found.',
        ], 404);

    }

    /**
     * Company requests client solicitation
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function companySolicitation(Request $request)
    {

        $results = Client::where('id', $request->get('client_id'))->whereHas('companies', function($query) use ($request){
            $query->where('company_id', $request->get('company_id'));
        })->count();

        if($results > 0){
            return response()->json([
                'message' => 'Client already added.',
                'status' => 422,
            ], 422);
        }

        $requested_by_client = $request->get('requested_by_client');

        $client = Client::find($request->get('client_id'));

        if($client){

            if(!$requested_by_client){
                $client->companies()->attach($request->get('company_id'), [
                    'is_confirmed' => false, 
                    'requested_by_client' => $requested_by_client
                ]);
            } else {

                $client->companies()->attach($request->get('company_id'), [
                    'is_confirmed' => false, 
                    'requested_by_client' => $requested_by_client,
                    'diets_edit' => $request->get('diets_edit'),
                    'diets_show' => $request->get('diets_show'),
                    'evaluations_edit' => $request->get('evaluations_edit'),
                    'evaluations_show' => $request->get('evaluations_show'),
                    'exams_edit' => $request->get('exams_edit'),
                    'exams_show' => $request->get('exams_show'),
                    'restrictions_edit' => $request->get('restrictions_edit'),
                    'restrictions_show' => $request->get('restrictions_show'),
                    'trainnings_edit' => $request->get('trainnings_edit'),
                    'trainnings_show' => $request->get('trainnings_show')
                ]);

            }

            //load relation to return
            $client_company = $client->companies()->select('id', 'name', 'slug')
                ->wherePivot('company_id', '=',$request->get('company_id'))
                ->withPivot('is_confirmed', 'is_deleted', 'requested_by_client')
                ->first();

            return response()->json(['message' => 'OK', 'company' => $client_company]);
        }

        if(!$client){
            return response()->json([
                'message' => 'Client not found.',
            ], 404);
        }

    }

    /**
     *  Client accept company solicitation
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function acceptCompanySolicitation(Request $request)
    {
        $client = Client::find($request->get('client_id'));

        if($client){

            $client->companies()->updateExistingPivot($request->get('company_id'),
                [
                    'is_confirmed' => true,
                    'is_deleted' => false,
                    'confirmed_by_id' => \Auth::user()->id,
                    'confirmed_by_type' => get_class(\Auth::user()),
                    'confirmed_at' => Carbon::now(),
                    'deleted_by_id' => null,
                    'deleted_by_type' => null,
                    'deleted_at' => null
                ]);


            //load relation to return
            $client_company = $client->companies()->select('id', 'name', 'slug')
                ->wherePivot('company_id', '=',$request->get('company_id'))
                ->withPivot('is_confirmed', 'requested_by_client')
                ->first();

            return response()->json(['message' => 'OK', 'company' => $client_company]);
        }

        if(!$client){
            return response()->json([
                'message' => 'Client not found.',
            ], 404);
        }

    }


    /**
     *  Client remove company solicitation
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function removeCompanySolicitation(Request $request)
    {
        $client = Client::find($request->get('client_id'));

        if($client){

            $client->companies()->updateExistingPivot($request->get('company_id'),
                ['is_deleted' => true,
                    'is_confirmed' => false,
                    'deleted_by_id' => \Auth::user()->id,
                    'deleted_by_type' => get_class(\Auth::user()),
                    'deleted_at' => Carbon::now()
                ]);

            return response()->json(['message' => 'OK']);
        }

        if(!$client){
            return response()->json([
                'message' => 'Client not found.',
            ], 404);
        }

    }

    /**
     *  Update client company relationship
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateCompanyRelationship(Request $request)
    {
        $client = Client::find($request->get('client_id'));

        if($client){

            $client->companies()->updateExistingPivot($request->get('company_id'), $request->all());

            return response()->json(['message' => 'Relationship updated']);
        }

        if(!$client){
            return response()->json([
                'message' => 'Client not found.',
            ], 404);
        }

    }

    /**
     *  Reactivate client company relationship
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function reactivateCompanyRelationship(Request $request)
    {
        $client = Client::find($request->get('client_id'));

        if($client){

            $client->companies()->updateExistingPivot($request->get('company_id'),
                [
                    'is_deleted' => false,
                    'deleted_by_id' => null,
                    'deleted_by_type' => null,
                    'deleted_at' => null
                ]
            );

            return response()->json(['message' => 'Relationship reactivated']);
        }

        if(!$client){
            return response()->json([
                'message' => 'Client not found.',
            ], 404);
        }

    }

    /**
     *  Check Slug
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    public function check_slug($slug)
    {
        $client = Client::where('slug', $slug)->first();

        if($client){
            $already_exist = true;
        } else {
            $already_exist = false;
        }

        return response()->json([
            'already_exist' => $already_exist,
        ], 200);
    }

    /**
     * Generate new Password to the user and send the email for him.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateNewPass($email)
    {
        $user = Client::where(['email' => $email])->first();

        if(!$user){
            return response()->json(['alert' => ['type' => 'success', 'title' => 'Atenção!', 'message' => 'Email não localizado', 'status_code' => 404]], 404);
        }

        $pass = rand(100000, 999999);

        $user->password = bcrypt($pass);
        $user->save();

        //Email
        $data = [];
        $data['full_name'] = $user->full_name;
        $data['user_email'] = $user->email;
        $data['align'] = 'center';
        $data['messageTitle'] = 'Olá, ' . $user->full_name;
        $data['messageOne'] = 'Alguém solicitou recentemente uma alteração na senha da sua conta do iSaudavel';
        $data['messageTwo'] = 'Caso não tenha sido você, acesse sua conta vinculada a este email e altere a senha para sua segurança.';
        $data['messageThree'] = 'Nova senha:';
        $data['button_link'] = 'https://app.isaudavel.com';
        $data['button_name'] = $pass;
        $data['messageFour'] = 'Para manter sua conta segura, não encaminhe este e-mail para ninguém.';
        $data['messageSubject'] = 'Alteração de senha iSaudavel';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data){
            $message->from('no-reply@isaudavel.com', 'iSaudavel');
            $message->to($data['user_email'], $data['full_name'])->subject($data['messageSubject']);
        });

        if(!count(\Mail::failures())) {
            return response()->json(['alert' => ['type' => 'success', 'title' => 'Atenção!', 'message' => 'Senha alterada com sucesso.', 'status_code' => 200]], 200);
        }

        if(count(\Mail::failures())){
            return response()->json(['alert' => ['type' => 'error', 'title' => 'Atenção!', 'message' => 'Ocorreu um erro ao enviar o e-mail.', 'status_code' => 500]], 500);
        }

        return view('users.show', compact('user'));
    }


}
