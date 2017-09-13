<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Professional;
use App\Models\ProfessionalPhoto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProfessionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $professionals = Professional::orderBy('name', 'asc')->paginate(10);

        return response()->json(custom_paginator($professionals));
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function companyProfessionals(Request $request)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $per_page = 10;

        $professionals = Professional::whereHas('companies', function ($query) use($request){
            $query->where('company_id', $request->get('company_id'));
        })->with('categories')->orderBy('name')->get();


        $verified_professionals = [];
        foreach ($professionals as $professional){
            //check if is a company professional
            $is_professional = $professional->companies->contains($request->get('company_id'));

            //check if is confirmed
            $is_confirmed = $professional->companies()
                ->wherePivot('company_id', '=',$request->get('company_id'))
                ->wherePivot('is_confirmed', '=', true)->count();

            $is_public = $professional->companies()
                ->wherePivot('company_id', '=',$request->get('company_id'))
                ->wherePivot('is_public', '=', true)->count();

            $is_admin = $professional->companies()
                ->wherePivot('company_id', '=',$request->get('company_id'))
                ->wherePivot('is_admin', '=', true)->count();

            $professional['is_professional'] = $is_professional;
            $professional['is_confirmed'] = $is_confirmed ? true : false;
            $professional['is_public'] = $is_public ? true : false;
            $professional['is_admin'] = $is_admin ? true : false;
            $verified_professionals[] = $professional->makeHidden(['companies']);
        }

        $verified_professionals = collect($verified_professionals);

        $currentPageItems = $verified_professionals->slice(($currentPage - 1) * $per_page, $per_page);

        $paged_professionals =  new LengthAwarePaginator($currentPageItems->flatten(), count($verified_professionals), $per_page);

        return response()->json(custom_paginator($paged_professionals, 'professionals'));
    }


    /**
     * Professional Search.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $per_page = 10;

        $search = explode(' ', $request->get('search'));

        $professionals = Professional::where(function ($query) use ($search, $request) {
            $query->where('name', 'LIKE', '%' . $request->get('search') . '%');
            $query->orWhere('last_name', 'LIKE', '%' . $request->get('search') . '%');
            $query->orWhere('email', 'LIKE', '%' . $request->get('search') . '%');

            //for full name
            $query->orWhereIn('name', $search);
            $query->orWhere(function ($query) use ($search) {
                $query->whereIn('last_name', $search);
            });

        })->orderBy('name')->get();

        $verified_professionals = [];
        foreach ($professionals as $professional){
            //check if is a company professional
            $is_professional = $professional->companies->contains($request->get('company_id'));

            //check if is confirmed
            $is_confirmed = $professional->companies()
                ->wherePivot('company_id', '=',$request->get('company_id'))
                ->wherePivot('is_confirmed', '=', true)->count();

            $is_admin = $professional->companies()
                ->wherePivot('company_id', '=',$request->get('company_id'))
                ->wherePivot('is_admin', '=', true)->count();

            $professional['is_professional'] = $is_professional;
            $professional['is_confirmed'] = $is_confirmed ? true : false;
            $professional['is_admin'] = $is_admin ? true : false;
            $verified_professionals[] = $professional->makeHidden(['companies']);
        }

        $verified_professionals = collect($verified_professionals);

        $currentPageItems = $verified_professionals->slice(($currentPage - 1) * $per_page, $per_page);

        $paged_professionals =  new LengthAwarePaginator($currentPageItems->flatten(), count($verified_professionals), $per_page);

        return response()->json(custom_paginator($paged_professionals, 'professionals'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $checkProfessional = Professional::where('email', $request->get('email'))->first();

        if($checkProfessional){
            return response()->json([
                'message' => 'Professional already exist.',
                'status' => 422
            ]);
        }

        if($request->has('password') && $request->get('password')){
            $pass = $request->get('password');
        } else {
            $pass = str_random(6);
        }

        $request->merge([
            'password' => bcrypt($pass),
            'remember_token' => str_random(10)
        ]);

        $professional = Professional::create($request->all());

        //Attach professional categories
        $professional->categories()->attach($request->get('categories'));

        //If is a company creating a professional attach automatically
        if($request->has('company_id') && $request->get('company_id')){
            $professional->companies()->attach($request->get('company_id'),
                [
                    'is_confirmed' => true,
                    'confirmed_by_id' => \Auth::user()->id,
                    'confirmed_by_type' => get_class(\Auth::user()),
                    'confirmed_at' => Carbon::now()
                ]);


            //Envia email para informar o cliente do cadastro
            $data = [];
            $data['align'] = 'center';
            $data['messageTitle'] = '<h4>Cadastro iSaudavel</h4>';
            $data['messageOne'] = '
            <p>Olá ' . $request->get('name') . ',</p>
            <p>O profissional <b>' . \Auth::user()->full_name . '</b> acabou de criar um <b>Usuário Profissional</b> para você na plataforma <a href="https://isaudavel.com" target="_blank">iSaudavel</a>.
            </p>
            <p>Veja o que você poderá fazer na plataforma:</p>
            <p>Controle de agendamentos</p>
            <p>Informações sobre a saúde de seus clientes como avaliações físicas, exames, dietas, treinamentos, observações.</p>
            <p>Perfil profissional público para você divulgar seu trabalho</p>
            <p>Cadastro de cursos e certificados</p>
            <p>Avaliações de seus clientes</p>
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
            'message' => 'Professional created.',
            'professional' => $professional->fresh(['photos'])
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
        $professional = Professional::with(['photos', 'categories', 'companies', 'certifications', 'last_ratings', 'last_recomendations'])->find($id);

        return response()->json(['professional' => $professional]);
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

        $professional = tap(Professional::find($request->get('id')))->update($request->all());

        // Sync categories
        $professional->categories()->sync($request->get('categories'));

        //update photos
        if (array_key_exists('photos', $request->all())) {
            foreach ($request->get('photos') as $photo) {
                ProfessionalPhoto::find($photo['id'])->update($photo);
            }
        }

        return response()->json([
            'message' => 'Professional updated.',
            'professional' => $professional->fresh()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfessionalCompanyRelationship(Request $request)
    {

        $professional = Professional::find($request->get('professional_id'));

        $professional->companies()->detach($request->get('company_id'));

        $professional->companies()->attach($request->get('company_id'),
                [
                    'is_admin' => $request->get('is_admin'),
                    'is_confirmed' => $request->get('is_confirmed'),
                    'is_public' => $request->get('is_public'),
                ]);

        return response()->json([
            'message' => 'Relationship of professional - company updated.',
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
        $destroyed = Professional::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Professional destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Professional not found.',
        ], 404);

    }

    /**
     * Search professionals by given category
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function searchByCategory(Request $request)
    {
        $professionals = Professional::whereHas('categories', function($query) use($request){

            if($request->get('category') === 'all'){
                $query->where('slug', '<>', 'all');
            }

            if($request->get('category') != 'all'){
                $query->where('slug', $request->get('category'));
            }

        })->with(['categories' => function($query){
            $query->select('name');
        }])->orderBy('name', 'asc')->get();

        return response()->json(['count' => $professionals->count(), 'data' => $professionals]);
    }

    /**
     * Company requests professional solicitation
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function companySolicitation(Request $request)
    {
        $professional = Professional::find($request->get('professional_id'));

        if($professional){

            $professional->companies()->attach($request->get('company_id'), ['is_confirmed' => false]);

            //Envia email para informar usuário da solicitação da empresa
            $data = [];
            $data['align'] = 'center';
            $data['messageTitle'] = '<h4>Solicitação de empresa</h4>';
            $data['messageOne'] = '
            <p>Olá ' . $professional->full_name . ',</p>
            <p>O profissional <b>' . $request->get('user_full_name') . '</b> acabou de adicionar você na empresa <b> ' .$request->get('company_name') .'</b>.
            </p>
            <p>Acesse seu Dashboard profissional para aceitar ou excluir a solicitação desta empresa.</p>
            <br>
            <p>Acesse online em <a href="https://app.isaudavel.com">app.isaudavel.com</a> ou baixe o aplicativo 
            para <a href="https://play.google.com/store/apps/details?id=com.isaudavel" target="_blank">Android</a> e <a href="https://itunes.apple.com/us/app/isaudavel/id1277115133?mt=8" target="_blank">iOS (Apple)</a></p>.';

            $data['messageSubject'] = $professional->full_name . ' a empresa ' . $request->get('company_name') . ' adicionou você.';

            \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data, $professional){
                $message->from('no-reply@isaudavel.com', 'iSaudavel App');
                $message->to($professional->email, $professional->full_name)->subject($data['messageSubject']);
            });

            return response()->json(['message' => 'OK']);
        }

        if(!$professional){
            return response()->json([
                'message' => 'Professional not found.',
            ], 404);
        }

    }

    /**
     *  Professional accept company solicitation
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function acceptCompanySolicitation(Request $request)
    {
        $professional = Professional::find($request->get('professional_id'));

        if($professional){

            $professional->companies()->updateExistingPivot($request->get('company_id'),
                [
                    'is_confirmed' => true,
                    'confirmed_by_id' => \Auth::user()->id,
                    'confirmed_by_type' => get_class(\Auth::user()),
                    'confirmed_at' => Carbon::now()
                ]);

            return response()->json(['message' => 'OK']);
        }

        if(!$professional){
            return response()->json([
                'message' => 'Professional not found.',
            ], 404);
        }

    }

    /**
     *  Professional remove company solicitation
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function removeCompanySolicitation(Request $request)
    {
        $professional = Professional::find($request->get('professional_id'));

        if($professional){

            $professional->companies()->detach($request->get('company_id'));

            return response()->json(['message' => 'OK']);
        }

        if(!$professional){
            return response()->json([
                'message' => 'Professional not found.',
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
        $professional = Professional::where('slug', $slug)->first();

        if($professional){
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
        $user = Professional::where(['email' => $email])->first();

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
