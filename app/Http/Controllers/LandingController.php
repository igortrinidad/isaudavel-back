<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Lead;


class LandingController extends Controller
{


    /**
     * Index
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('prelaunch.index');

    }

    /**
     * Index teste
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function LeadStoreForm(Request $request)
    {

        $lead = Lead::create($request->all());


        //Email
        $data = [];
        $data['align'] = 'left';

        $data['messageTitle'] = 'LEAD WESKD';
        $data['messageOne'] = 'Nome: ' . $request['name'];
        $data['messageTwo'] = 'Email: ' . $request['email'];
        $data['messageThree'] = 'Phone: ' . $request['phone'];
        $data['messageSubject'] = 'WESKD: Acabamos de receber um lead no WESKD';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data){
            $message->from('no-reply@weplaces.com.br', 'Landing WESKD');
            $message->to('contato@maisbartenders.com.br', 'WESKD')->subject($data['messageSubject']);
        });

        return 'Mensagem enviada com sucesso';

    }

    
}

