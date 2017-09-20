<?php

namespace App\Console\Commands;

use App\Mail\CompanyInvoiceReminder;
use App\Models\CompanyInvoice;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendCompanyInvoiceReminder1Day extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:company-reminder-1-day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a reminder of open invoices 1 day before expiration to companies';

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

        $invoices = CompanyInvoice::where('expire_at', $expire_at)
            ->where('is_confirmed', false)
            ->where('is_canceled', false)
            ->with('subscription', 'company')
            ->get();

        $this->info('Faturas localizadas: '. $invoices->count());

        foreach ($invoices as $invoice){

            $owner = $invoice->company->owner;

            if(!$owner->email || !filter_var($owner->email, FILTER_VALIDATE_EMAIL)){
                $this->warn($invoice->company->name. ' sem e-mail cadastrado ou não aceito.');
                continue;
            }

            //Avoid send email to test domains
            if (preg_match("/example/i", $owner->email) || preg_match("/teste/i", $owner->email) || preg_match("/test/i", $owner->email)) {

                $this->warn('O email não pode ser enviado para ' .  $invoice->company->name. ' ('.$owner->email.')');
                continue;
            }

            $data = [];
            $data['align'] = 'center';
            $data['messageTitle'] = 'Olá, '. $owner->full_name;
            $data['messageOne'] = 'Estamos passando apenas para lembrá-lo(a) que sua fatura referente a assinatura da empresa '. $invoice->company->name. ' com valor de R$'. number_format($invoice->total,2,',', '.'). ' vence amanhã ('. $invoice->expire_at.').';
            $data['messageTwo'] = 'Caso você já tenha feito o pagamento da sua fatura, por favor, desconsidere essa mensagem.';
            $data['messageThree'] = 'Acesse online em https://isaudavel.com ou baixe o aplicativo para Android e iOS (Apple)';
            $data['messageSubject'] = 'Lembrete de fatura em aberto';

            \Mail::to($owner->email, $owner->full_name)->queue(new CompanyInvoiceReminder($data));

            $this->info('Email enviado para ' .  $invoice->company->name. ' ('.$owner->email.')');

        }

        $this->info('Finished' );
    }
}
