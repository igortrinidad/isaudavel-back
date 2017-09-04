<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;


class SystemController extends Controller
{


    /**
     * Index
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function send_log_erro(Request $request)
    {
        
        //Email para nós
        $data = [];
        $data['align'] = 'center';

        $data['messageTitle'] = 'Erro no iSaudavel';
        $data['messageOne'] = $request->get('log');
        $data['messageSubject'] = 'Log de erro';

        $mail = \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data){
            $message->from('no-reply@isaudavel.com', 'Erro iSaudavel');
            $message->to('contato@maisbartenders.com.br', 'Erro iSaudavel')->subject($data['messageSubject']);
            $message->cc('contato@matheuslima.com.br', 'Erro iSaudavel')->subject($data['messageSubject']);
        });

        return response()->json(['mail' => $mail]);


    }

}
