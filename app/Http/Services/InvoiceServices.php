<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Mail;

class InvoiceServices{

    public  function SendNewInvoiceMail($invoice){
        $owner = $invoice->company->owner;

        $data = [];
        $data['align'] = 'center';
        $data['messageTitle'] = 'Olá, '. $owner->full_name;
        $data['messageOne'] = 'Uma nova fatura foi emitida no iSaudavel no valor de R$'. number_format($invoice->total,2,',', '.').' com vencimento para '. $invoice->expire_at.'.';
        $data['messageTwo'] = 'Acesse online em https://isaudavel.com ou baixe o aplicativo para Android e iOS (Apple)';
        $data['messageSubject'] = 'Nova fatura recebida';

        Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data, $owner){
            $message->from('no-reply@isaudavel.com', 'iSaudavel - sua saúde em boas mãos.');
            $message->to($owner->email, $owner->full_name)->subject($data['messageSubject']);
        });
    }
}