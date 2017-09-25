<?php

namespace App\Http\Controllers;

use App\Http\Services\InvoiceServices;
use App\Http\Services\SubscriptionServices;
use App\Models\ClientSubscription;
use App\Models\CompanyInvoice;
use App\Models\CompanySubscription;
use App\Models\OracleUser;
use App\Models\Event;
use App\Models\MealRecipe;
use App\Models\Evaluation;
use App\Models\SubscriptionHistory;
use App\Models\EvaluationIndex;
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

    /**
     * Login usuário Oracle
     */
    public function showLogin()
    {
        return view('oracle.auth.login');
    }


    public function index()
    {
        return view('oracle.dashboard.index');
    }

    /**
     * LISTA COMPANIES
     */
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

    /**
     * EDIT COMPANY
     */
    public function companyEdit($id)
    {
        $company = Company::with('subscription.histories', 'categories', 'professionals.categories')->find($id);

        \JavaScript::put(['company' => $company]);

        return view('oracle.dashboard.companies.edit', compact('company'));
    }

    /**
     * Atualiza a COMPANY
     */
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

    /**
     * Atualiza uma subscription da COMPANY na plataforma iSaudavel
     */
    public function subscriptionUpdate(Request $request)
    {
        $this->subscriptionServices->updateSubscription($request);

        return redirect()->back();
    }

    /**
     * Create de subscription da COMPANY na plataforma
     */
    public function subscriptionCreate($id)
    {
        $company = Company::with('categories', 'professionals')->find($id);

        \JavaScript::put(['company' => $company]);

        return view('oracle.dashboard.companies.subscription-create', compact('company'));
    }

    /**
     * Salva a subscription da company
     */
    public function subscriptionStore(Request $request)
    {
        $this->subscriptionServices->createSubscription($request);

        return redirect()->route('oracle.dashboard.companies.list');

    }

    /**
     * Lista as invoices da company na plataforma iSaudavel
     */
    public function companyInvoices($id)
    {
        $invoices = CompanyInvoice::where('company_id', $id)->orderByDesc('expire_at')->orderByDesc('created_at')->paginate(10);

        return view('oracle.dashboard.companies.invoices', compact('invoices'));
    }

    /**
     * Mostra a invoice da company na plataforma iSaudavel
     */
    public function invoiceShow($company_id, $invoice_id)
    {
        $invoice = CompanyInvoice::with('company.owner')->find($invoice_id);

        \JavaScript::put(['invoice' => $invoice]);

        return view('oracle.dashboard.companies.invoice-show', compact('invoice'));
    }

    /**
     * Atualiza uma invoice da empresa com a plataforma iSaudavel
     */
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

    /**
     * Lista os usuários CLIENT
     */
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

    /**
     * Mostra o perfil de usuário CLIENT
     */
    public function clientShow($id)
    {
        $client = Client::with( 'companies.categories')->find($id);

        \JavaScript::put(['client' => $client]);

        return view('oracle.dashboard.clients.show', compact('client'));
    }

    /**
     * Atualiza o perfil de usuário CLIENT
     */
    public function clientUpdate(Request $request)
    {
        $client = tap(Client::find($request->get('id')))->update($request->all())->fresh();

        flash('Cliente atualizado com sucesso')->success()->important();

        return redirect()->back();
    }

    /**
     * Lista os usuários PROFISSIONAL
     */
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

    /**
     * Mostra o perfil de usuário PROFISSIONAL
     */
    public function professionalShow($id)
    {
        $professional = Professional::with('categories', 'companies.categories')->find($id);

        \JavaScript::put(['professional' => $professional]);

        return view('oracle.dashboard.professionals.show', compact('professional'));
    }

    /**
     * Atualiza o perfil de usuario PROFISSIONAL
     */
    public function professionalUpdate(Request $request)
    {
        $categories = json_decode($request->get('categories'));

        $professional = tap(Professional::find($request->get('id')))->update($request->all())->fresh();

        $professional->categories()->sync($categories);

        flash('Profissional atualizado com sucesso')->success()->important();

        return redirect()->back();
    }

    /**
     * Lista de usuários ORACLE
     */
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

    /**
     * Mostra o perfil do usuário ORACLE
     */
    public function oracleShow($id)
    {
        $oracle = OracleUser::find($id);

        \JavaScript::put(['oracle' => $oracle]);

        return view('oracle.dashboard.oracles.show', compact('oracle'));
    }

    /**
     * Atualiza o perfil do usuário ORACLE
     */
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


    /**
     * Mostra o perfil do usuário ORACLE
     */
    public function profileShow()
    {
        $oracle = \Auth::user();

        return view('oracle.dashboard.profile.index', compact('oracle'));
    }


    /**
     * Lista os eventos
     */
    public function eventsList(Request $request)
    {
        $events = Event::where('name', 'LIKE', '%' .$request->query('search').'%')->with('from')->paginate(20);

        return view('oracle.dashboard.events.list', compact('events'));
    }

    /**
     * Lista as receitas cadastradas por todos os usuários
     */
    public function recipesList(Request $request)
    {
        $recipes = MealRecipe::where('title', 'LIKE', '%' .$request->query('search').'%')->with('from', 'type')->paginate(20);

        return view('oracle.dashboard.recipes.list', compact('recipes'));
    }


    /**
     * lista os indices de avaliação
     */
    public function eval_index_list(Request $request)
    {
        $eval_index = EvaluationIndex::where('label', 'LIKE', '%' .$request->query('search').'%')->with('from')->orderBy('created_at', 'DESC')->paginate(40);

        return view('oracle.dashboard.eval-index.list', compact('eval_index'));
    }

    /**
     * Mostra o indice de avaliação para editar
     */
    public function eval_index_edit($id)
    {
        $old_eval_index = EvaluationIndex::find($id);
        $old_eval_index = $old_eval_index->label;

        return view('oracle.dashboard.eval-index.edit', compact('old_eval_index'));
    }

    /**
     * Atualiza o indice de avaliações e atualiza todas as avaliações que o utilizam
     */
    public function update_eval_index(Request $request)
    {
        $eval_index = EvaluationIndex::where('label', $request->get('old_eval_index') )->update(['label' => $request->get('new_eval_index')]);

        $evals = Evaluation::where('items', 'like', '%' . $request->get('old_eval_index') . '%')
        ->get();



        foreach($evals as $eval){

            $items = $eval['items'];
            $updated_items = [];
            foreach($items as $item){

                if( $item['label'] == $request->get('old_eval_index') ){
                    $item['label'] = $request->get('new_eval_index');
                }

                $updated_items[] = $item;
            }

            //pass updated items to evaluation items
            $eval->items = $updated_items;

            $eval->update($eval->toArray());
        }

        return redirect( route('oracle.dashboard.eval-index.list') );
    }

}
