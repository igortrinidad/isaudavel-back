<?php

namespace App\Http\Controllers;

use App\Http\Services\InvoiceServices;
use App\Http\Services\SubscriptionServices;
use App\Models\ClientSubscription;
use App\Models\CompanyInvoice;
use App\Models\CompanySubscription;
use App\Models\Modality;
use App\Models\OracleNotification;
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
        $is_paid = $request->get('is_paid') ? true : false;

        $request->merge(['is_delivery' => $is_delivery, 'address_is_available' => $address_is_available, 'address' => $address, 'is_active' => $is_active, 'is_paid' => $is_paid]);

        $company = tap(Company::find($request->get('id')))->update($request->all())->fresh();

        if($request->has('has_professionals_to_remove') && $request->get('has_professionals_to_remove') == 'true'){
            $company->professionals()->detach($professionals_to_remove);
        }

        flash('Empresa atualizada com sucesso')->success()->important();

        return redirect()->back();
    }


    /**
     * Mostra a assinatura da empresa
     */
    public function companySubscription($id)
    {
        $company = Company::with('subscription.histories.user', 'categories', 'professionals')->find($id);

        \JavaScript::put(['company' => $company]);

        return view('oracle.dashboard.companies.subscription', compact('company'));
    }

    /**
     * Lista de profissionais da empresa
     */
    public function companyProfessionalList($id)
    {
        $company = Company::with('professionals')->find($id);

        return view('oracle.dashboard.companies.professional-list', compact('company'));
    }

    /**
     * Excluir um usuário da empresa
     */
    public function removeProfessionalFromCompany(Request $request)
    {

        $professional = Professional::find($request->get('professional_id'));

        if($professional){

            $professional->companies()->detach($request->get('company_id'));

            return redirect()->back();
        }

        return redirect()->back();
    }

    /**
     * Adiciona um usuário existente para a empresa
     */
    public function addProfessionalToCompany(Request $request)
    {
        //dd($request->all());

        $professional = Professional::where('email', $request->get('email'))->first();

        if($request->get('is_confirmed') == 'on'){
            $request->is_confirmed = true;
        } else {
           $request->is_confirmed = false;
        }

        if($request->get('is_admin') == 'on'){
            $request->is_admin = true;
        } else {
           $request->is_admin = false;
        }

        if($request->get('is_public') == 'on'){
            $request->is_public = true;
        } else {
           $request->is_public = false;
        }

        $request->merge([
            'is_admin' => $request->is_admin,
            'is_confirmed' => $request->is_confirmed,
            'is_public' => $request->is_public,
        ]);

        if($professional){

            $professional->companies()->attach($request->get('company_id'),
                [
                    'is_admin' => $request->get('is_admin'),
                    'is_confirmed' => $request->get('is_confirmed'),
                    'is_public' => $request->get('is_public'),
                ]);

            return redirect()->back();

        } else {

           return redirect()->back(); 

        }

        return redirect()->back();
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

        \JavaScript::put(['events' => $events]);

        return view('oracle.dashboard.events.list', compact('events'));
    }

    /**
     * Edit do evento
     */
    public function editEvent($id)
    {
        $event = Event::find($id);

        \JavaScript::put(['event' => $event]);

        return view('oracle.dashboard.events.edit', compact('event'));
    }

    /**
     * Update do evento
     */
    public function eventUpdate(Request $request)
    {
        $is_published = $request->get('is_published') ? true : false;

        $request->merge(['is_published' => $is_published]);

        $event = tap(Event::find($request->get('id')))->update($request->all())->fresh();

        flash('Evento atualizado com sucesso')->success()->important();

        return redirect()->route('oracle.dashboard.events.list');
    }

    /**
     * Destroy do evento
     */
    public function destroyEvent(Request $request)
    {
       $event = Event::with('from', 'participants.participant')->find($request->get('event_id'));

        //Send Mail to event creator
        $data = [];
        $data['align'] = 'center';

        $data['messageTitle'] = '<h4>Evento removido</h4>';
        $data['messageOne'] = 'Informamos que o seu evento '.$event->name.' foi removido da plataforma iSaudavel pelo motivo abaixo.';
        $data['messageTwo'] = '<strong>Motivo:</strong> <br>'.$request->get('remove_reason');

        $data['messageSubject'] = 'Evento removido';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data, $event){
            $message->from('no-reply@isaudavel.com', 'iSaudavel App');
            $message->to($event->from->email,$event->from->full_name)->subject($data['messageSubject']);
        });

        //Send Mail to event participants
        foreach($event->participants as $event_participant){
            $data = [];
            $data['align'] = 'center';

            $data['messageTitle'] = '<h4>Cancelamento de evento</h4>';
            $data['messageOne'] = 'Informamos que o evento '. $event->name . ' marcado para '.$event->date->format('d/m/Y'). ' às '.$event->time.', foi cancelado pelo motivo abaixo:';
            $data['messageTwo'] = '<strong>Motivo:</strong> <br>'.$request->get('remove_reason');

            $data['messageSubject'] = 'Cancelamento de evento';

            \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data, $event_participant){
                $message->from('no-reply@isaudavel.com', 'iSaudavel App');
                $message->to($event_participant->participant->email, $event_participant->participant->full_name)->subject($data['messageSubject']);
            });
        }

        $event->delete();

        return response()->json(['message' => 'Event removed']);

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
     * Edit da receita
     */
    public function editRecipe($id)
    {
        $recipe = MealRecipe::find($id);

        \JavaScript::put(['recipe' => $recipe]);

        return view('oracle.dashboard.recipes.edit', compact('recipe'));
    }

    /**
     * Update da receita
     */
    public function recipeUpdate(Request $request)
    {
        $is_published = $request->get('is_published') ? true : false;

        $request->merge(['is_published' => $is_published]);

        $recipe = tap(MealRecipe::find($request->get('id')))->update($request->all())->fresh();

        flash('Receita atualizada com sucesso')->success()->important();

        return redirect()->route('oracle.dashboard.recipes.list');
    }

    /**
     * Destroy da receita
     */
    public function destroyRecipe(Request $request)
    {
        $recipe = MealRecipe::with('from')->find($request->get('meal_recipe_id'));

        //Send Mail to recipe creator
        $data = [];
        $data['align'] = 'center';

        $data['messageTitle'] = '<h4>Receita removida</h4>';
        $data['messageOne'] = 'Informamos que o sua receita de '.$recipe->title.' foi removida da plataforma iSaudavel pelo motivo abaixo.';
        $data['messageTwo'] = '<strong>Motivo:</strong> <br>'.$request->get('remove_reason');

        $data['messageSubject'] = 'Receita removida';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data, $recipe){
            $message->from('no-reply@isaudavel.com', 'iSaudavel App');
            $message->to($recipe->from->email, $recipe->from->full_name)->subject($data['messageSubject']);
        });

        $recipe->delete();

        return response()->json(['message' => 'Recipe removed']);

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


    /**
     * Lista de modalidades
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function modalitiesList(Request $request)
    {
        $modalities = Modality::where(function ($query) use ($request) {

            if($request->has('search') && !empty($request->has('search'))) {
                $query->where('name', 'LIKE', '%' . $request->query('search') . '%');
            }

        })->withCount('submodalities', 'events')->paginate(20);

        \JavaScript::put(['modalities' => $modalities]);

        return view('oracle.dashboard.modalities.list', compact('modalities'));
    }

    /**
     * Create da modalidade
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createModality()
    {
        return view('oracle.dashboard.modalities.create');
    }


    /**
     * Store modalidade
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeModality(Request $request)
    {
        $submodalities =  json_decode($request->get('submodalities'), true);


        $modality = Modality::create($request->all());

        foreach ($submodalities as $submodality) {
            $modality->submodalities()->create($submodality);
        }

        flash('Modalidade adicionada com sucesso')->success()->important();

        return redirect()->route('oracle.dashboard.modalities.list');
    }

    /**
     * Edit da modalidade
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editModality($id)
    {
        $modality = Modality::with('submodalities')->find($id);

        \JavaScript::put(['modality' => $modality]);

        return view('oracle.dashboard.modalities.edit', compact('modality'));
    }

    /**
     * Update modalidade
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function modalityUpdate(Request $request)
    {
        $modality = tap(Modality::find($request->get('id')))->update($request->all())->fresh();

        flash('Modalidade atualizada com sucesso')->success()->important();

        return redirect()->route('oracle.dashboard.modalities.list');
    }

    /**
     * Excluir modalidade
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @internal param $id
     */
    public function destroyModality(Request $request)
    {
        $modality = Modality::find($request->get('modality_id'));

        $modality->submodalities()->delete();

        $modality->delete();

        return response()->json(['message' => 'modality removed']);
    }


    /**
     * Notificações do usuário
     * @return \Illuminate\Http\JsonResponse
     */
    public function notifications()
    {
       $notifications = OracleNotification::where('oracle_user_id', \Auth::user()->id)
       ->orderByDesc('created_at')
       ->paginate(10);

        \JavaScript::put(['notifications' => $notifications]);

        return view('oracle.dashboard.notifications.index', compact('notifications'));
    }

    /**
     * Notificações do usuário
     * @return \Illuminate\Http\JsonResponse
     */
    public function followUp()
    {
        $latest_professionals = Professional::latest('created_at')->take(10)->get();
        $latest_companies = Company::latest('created_at')->take(10)->get();

        return view('oracle.dashboard.follow-up.index', compact('latest_professionals', 'latest_companies'));
    }

    /**
     * Criar usuário oracle
     * @return \Illuminate\Http\JsonResponse
     */
    public function newOracle()
    {
        return view('oracle.dashboard.oracles.create');
    }

    /**
     * Salva usuário oracle
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function StoreNewOracle(Request $request)
    {
        if($request->has('password') && !empty($request->get('password')))
        {
            if($request->get('password') != $request->get('confirm_password')){
                flash('As senhas devem ser iguais.')->error()->important();
                return redirect()->back()->withInput($request->except(['password', 'confirm_password']));
            }

            $password = bcrypt($request->get('password'));

            $request->merge(['password' => $password]);
        }

        $oracle_user = OracleUser::create($request->all());

        flash('Administrador adicionado com sucesso')->success()->important();

        return redirect()->route('oracle.dashboard.oracles.list');
    }


}
