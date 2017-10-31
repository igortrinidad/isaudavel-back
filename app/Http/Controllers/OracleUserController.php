<?php

namespace App\Http\Controllers;

use App\Models\OracleUser;
use Illuminate\Http\Request;

class OracleUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $oracles = OracleUser::orderBy('name', 'asc')->paginate(10);

        return response()->json(custom_paginator($oracles));
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
            'password' => bcrypt($request->get('password')),
            'remember_token' => str_random(10)
        ]);

        $oracle = OracleUser::create($request->all());

        return response()->json([
            'message' => 'Oracle created.',
            'oracle' => $oracle
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
        $oracle = OracleUser::find($id);

        return response()->json(['data' => $oracle]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if($request->has('password')){
            $request->merge([
                'password' => bcrypt($request->get('password')),
            ]);
        }

        $oracle = tap(OracleUser::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Oracle updated.',
            'oracle' => $oracle
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
        $destroyed = OracleUser::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Oracle destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Oracle not found.',
        ], 404);

    }

    /**
     * Generate new Password to the user and send the email for him.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateNewPass($email)
    {
        $user = OracleUser::where(['email' => $email])->first();

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
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fcmToken(Request $request)
    {
        $oracle_user = OracleUser::find($request->get('user_id'));

        if ($request->get('is_mobile') == 'true') {
            $oracle_user->fcm_token_mobile = $request->get('token');
            $oracle_user->save();
        }

        if ($request->get('is_mobile') == 'false') {
            $oracle_user->fcm_token_browser = $request->get('token');
            $oracle_user->save();
        }

        return response()->json(['message' => 'success']);
    }
}
