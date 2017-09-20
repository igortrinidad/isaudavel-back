<?php

namespace App\Console\Commands;

use App\Mail\ClientInvoiceReminder;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendClientInvoiceReminder1Day extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:client-reminder-1-day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a reminder of open invoices 1 days before expiration to clients';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Command started' );

        $expire_at = Carbon::now()->addDays(1)->format('Y-m-d');

        $invoices = Invoice::where('expire_at', $expire_at)
            ->where('is_confirmed', false)
            ->where('is_canceled', false)
            ->with('subscription.client', 'subscription.plan', 'company')
            ->get();

        $this->info('Faturas localizadas: '. $invoices->count());

        foreach ($invoices as $invoice){

            $client = $invoice->subscription->client;

            if(!$client->email || !filter_var($client->email, FILTER_VALIDATE_EMAIL)){
                $this->warn($client->name. ' sem e-mail cadastrado ou não aceito.');
                continue;
            }

            //Avoid send email to test domains
            if (preg_match("/example/i", $client->email) || preg_match("/teste/i", $client->email) || preg_match("/test/i", $client->email)) {

                $this->warn('O email não pode ser enviado para ' . $client->email);
                continue;
            }

            $data = [];
            $data['align'] = 'center';
            $data['messageTitle'] = 'Olá, '. $client->full_name;
            $data['messageOne'] = 'Estamos passando apenas para lembrá-lo(a) que sua fatura da empresa '. $invoice->company->name .', referente ao plano '.$invoice->subscription->plan->name.' com valor de R$'. number_format($invoice->value,2,',', '.'). ' vence amanhã ('. $invoice->expire_at.').';
            $data['messageTwo'] = 'Caso você já tenha feito o pagamento da sua fatura, por favor, desconsidere essa mensagem.';
            $data['messageThree'] = 'Acesse online em https://isaudavel.com ou baixe o aplicativo para Android e iOS (Apple)';
            $data['messageSubject'] = 'Lembrete de fatura em aberto';

            \Mail::to($client->email, $client->full_name)->queue(new ClientInvoiceReminder($data));

            $this->info('Email enviado para ' . $client->email);

        }

        $this->info('Finished' );
    }
}
