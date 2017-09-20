<?php

namespace App\Http\Controllers;

use App\Http\Services\InvoiceServices;
use App\Http\Services\SubscriptionServices;
use App\Models\ClientSubscription;
use App\Models\CompanyInvoice;
use App\Models\CompanySubscription;
use App\Models\OracleUser;
use App\Models\SubscriptionHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Lead;

use App\Models\Category;
use App\Models\Company;
use App\Models\Professional;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Integer;
use Webpatser\Uuid\Uuid;

class OracleController extends Controller
{
    /**
     * @var SubscriptionServices
     */
    private $subscriptionServices;

    /**
     * OracleController constructor.
     * @param SubscriptionServices $subscriptionServices
     */
    public function __construct(SubscriptionServices $subscriptionServices)
    {
        $this->subscriptionServices = $subscriptionServices;
    }

    public function showLogin()
    {
        return view('oracle.auth.login');
    }

    public function index()
    {
        return view('oracle.dashboard.index');
    }

    public function companiesList(Request $request)
    {
        if($request->has('search') && !empty($request->get('search'))){
            $companies  = Company::where(function($query) use($request){
                $query->where('name', 'LIKE', '%' . $request->get('search') . '%');
            })->paginate(10);

            $companies->appends(['search' => $request->get('search')]);
        }

        if(!$request->has('search') && empty($request->get('search')))
        {
            $companies = Company::orderBy('name')->paginate(10);
        }
        return view('oracle.dashboard.companies.list', compact('companies'));
    }

    public function companyEdit($id)
    {
        $company = Company::with('subscription.histories', 'categories', 'professionals.categories')->find($id);

        \JavaScript::put(['company' => $company]);

        return view('oracle.dashboard.companies.edit', compact('company'));
    }

    public function companyUpdate(Request $request)
    {

        $address = json_decode($request->get('address'));
        $professionals_to_remove = json_decode($request->get('professionals_to_remove'));


        //checkboxes
        $address_is_available = $request->get('address_is_available') ? true : false;
        $is_delivery = $request->get('is_delivery') ? true : false;
        $is_active = $request->get('is_active') ? true : false;

        $request->merge(['is_delivery' => $is_delivery, 'address_is_available' => $address_is_available, 'address' => $address, 'is_active' => $is_active]);

        $company = tap(Company::find($request->get('id')))->update($request->all())->fresh();

        if($request->has('has_professionals_to_remove') && $request->get('has_professionals_to_remove') == 'true'){
            $company->professionals()->detach($professionals_to_remove);
        }

        flash('Empresa atualizada com sucesso')->success()->important();

        return redirect()->back();
    }

    public function companySubscription($id)
    {
        $company = Company::with('subscription.histories.user', 'categories', 'professionals')->find($id);

        \JavaScript::put(['company' => $company]);

        return view('oracle.dashboard.companies.subscription', compact('company'));
    }

    public function subscriptionUpdate(Request $request)
    {
        $this->subscriptionServices->updateSubscription($request);

        return redirect()->back();
    }

    public function subscriptionCreate($id)
    {
        $company = Company::with('categories', 'professionals')->find($id);

        \JavaScript::put(['company' => $company]);

        return view('oracle.dashboard.companies.subscription-create', compact('company'));
    }

    public function subscriptionStore(Request $request)
    {
        $this->subscriptionServices->createSubscription($request);

        return redirect()->route('oracle.dashboard.companies.list');

    }

    public function companyInvoices($id)
    {
        $invoices = CompanyInvoice::where('company_id', $id)->orderByDesc('expire_at')->orderByDesc('created_at')->paginate(10);

        return view('oracle.dashboard.companies.invoices', compact('invoices'));
    }

    public function invoiceShow($company_id, $invoice_id)
    {
        $invoice = CompanyInvoice::with('company.owner')->find($invoice_id);

        \JavaScript::put(['invoice' => $invoice]);

        return view('oracle.dashboard.companies.invoice-show', compact('invoice'));
    }

    public function invoiceUpdate(Request $request)
    {
        $invoice = CompanyInvoice::find($request->get('id'));

        //checkboxes
        $is_confirmed = $request->get('is_confirmed') ? true : false;
        $is_canceled = $request->get('is_canceled') ? true : false;

        $confirmed_at = null;
        $canceled_at = null;

        if($is_confirmed && !$is_canceled){
            $confirmed_at = Carbon::now();
            $canceled_at = null;
        }

        if($is_canceled && !$is_confirmed){
            $canceled_at = Carbon::now();
            $confirmed_at = null;
        }

        $invoice_activity = [
            'full_name' =>\Auth::user()->full_name,
            'action' => 'invoice-updated',
            'label' => 'Fatura atualizada',
            'date' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $history = $invoice->history;
        $history = array_prepend($history, $invoice_activity);

        $request->merge(['is_confirmed' => $is_confirmed, 'confirmed_at' => $confirmed_at, 'is_canceled' => $is_canceled, 'canceled_at' => $canceled_at, 'history' =>$history]);

        $invoice->update($request->all());

        flash('Fatura atualizada com sucesso')->success()->important();

        return redirect()->back();
    }

    public function clientsList(Request $request)
    {
        $search = explode(' ', $request->get('search'))
        ;
        if($request->has('search') && !empty($request->get('search'))){

            $clients  =  Client::where(function($query) use($request, $search){
                $query->where('name', 'LIKE', '%' . $request->get('search') . '%');
                $query->orWhere('last_name', 'LIKE', '%' . $request->get('search') . '%');
                $query->orWhere('email', 'LIKE', '%' . $request->get('search') . '%');

                //for full name
                $query->orWhereIn('name', $search);
                $query->orWhere(function ($query) use ($search) {
                    $query->whereIn('last_name', $search);
                });
            })->paginate(10);

            $clients->appends(['search' => $request->get('search')]);
        }

        if(!$request->has('search') && empty($request->get('search')))
        {
            $clients = Client::orderBy('name')->paginate(10);
        }
        return view('oracle.dashboard.clients.list', compact('clients'));
    }

    public function clientShow($id)
    {
        $client = Client::with( 'companies.categories')->find($id);

        \JavaScript::put(['client' => $client]);

        return view('oracle.dashboard.clients.show', compact('client'));
    }

    public function clientUpdate(Request $request)
    {
        $client = tap(Client::find($request->get('id')))->update($request->all())->fresh();

        flash('Cliente atualizado com sucesso')->success()->important();

        return redirect()->back();
    }

    public function professionalsList(Request $request)
    {
        $search = explode(' ', $request->get('search'));

        if($request->has('search') && !empty($request->get('search'))){

            $professionals  =  Professional::where(function($query) use($request, $search){
                $query->where('name', 'LIKE', '%' . $request->get('search') . '%');
                $query->orWhere('last_name', 'LIKE', '%' . $request->get('search') . '%');
                $query->orWhere('email', 'LIKE', '%' . $request->get('search') . '%');

                //for full name
                $query->orWhereIn('name', $search);
                $query->orWhere(function ($query) use ($search) {
                    $query->whereIn('last_name', $search);
                });
            })->paginate(10);

            $professionals->appends(['search' => $request->get('search')]);
        }

        if(!$request->has('search') && empty($request->get('search')))
        {
            $professionals = Professional::orderBy('name')->paginate(10);
        }
        return view('oracle.dashboard.professionals.list', compact('professionals'));
    }

    public function professionalShow($id)
    {
        $professional = Professional::with('categories', 'companies.categories')->find($id);

        \JavaScript::put(['professional' => $professional]);

        return view('oracle.dashboard.professionals.show', compact('professional'));
    }

    public function professionalUpdate(Request $request)
    {
        $categories = json_decode($request->get('categories'));

        $professional = tap(Professional::find($request->get('id')))->update($request->all())->fresh();

        $professional->categories()->sync($categories);

        flash('Profissional atualizado com sucesso')->success()->important();

        return redirect()->back();
    }

    public function oraclesList(Request $request)
    {
        $search = explode(' ', $request->get('search'));

        if($request->has('search') && !empty($request->get('search'))){

            $oracles  =  OracleUser::where(function($query) use($request, $search){
                $query->where('name', 'LIKE', '%' . $request->get('search') . '%');
                $query->orWhere('last_name', 'LIKE', '%' . $request->get('search') . '%');
                $query->orWhere('email', 'LIKE', '%' . $request->get('search') . '%');

                //for full name
                $query->orWhereIn('name', $search);
                $query->orWhere(function ($query) use ($search) {
                    $query->whereIn('last_name', $search);
                });
            })->paginate(10);

            $oracles->appends(['search' => $request->get('search')]);
        }

        if(!$request->has('search') && empty($request->get('search')))
        {
            $oracles = OracleUser::orderBy('name')->paginate(10);
        }
        return view('oracle.dashboard.oracles.list', compact('oracles'));
    }

    public function oracleShow($id)
    {
        $oracle = OracleUser::find($id);

        \JavaScript::put(['oracle' => $oracle]);

        return view('oracle.dashboard.oracles.show', compact('oracle'));
    }

    public function oracleUpdate(Request $request)
    {


        if($request->has('current_password') && !empty($request->get('current_password'))){

            $user = \Auth::user();

            if(!\Hash::check($request->get('current_password'), $user->password)){

                flash('Senha atual incorreta')->error()->important();

                return redirect()->back();
            }
        }

        if($request->get('new_password') != $request->get('confirm_new_password'))
        {
            flash('As senhas devem ser iguais')->error()->important();


        }

        if( $request->get('confirm_new_password') == $request->get('new_password')){
            $request->merge([
                'password' => bcrypt($request->get('new_password')),
            ]);
        }

        $oracle = tap(OracleUser::find($request->get('id')))->update($request->all())->fresh();

        flash('Usuário atualizado com sucesso')->success()->important();

        return redirect()->back();
    }

    public function profileShow()
    {
        $oracle = \Auth::user();

        return view('oracle.dashboard.profile.index', compact('oracle'));
    }

}
