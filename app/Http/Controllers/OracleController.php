<?php

namespace App\Http\Controllers;

use App\Http\Services\InvoiceServices;
use App\Http\Services\SubscriptionServices;
use App\Models\CompanyInvoice;
use App\Models\CompanySubscription;
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
        $company = Company::with('subscription.histories', 'categories', 'professionals')->find($id);

        \JavaScript::put(['company' => $company]);

        return view('oracle.dashboard.companies.edit', compact('company'));
    }

    public function companyUpdate(Request $request)
    {
        $address = json_decode($request->get('address'));

        //checkboxes
        $address_is_available = $request->get('address_is_available') ? true : false;
        $is_delivery = $request->get('is_delivery') ? true : false;
        $is_active = $request->get('is_active') ? true : false;

        $request->merge(['is_delivery' => $is_delivery, 'address_is_available' => $address_is_available, 'address' => $address, 'is_active' => $is_active]);

        $company = tap(Company::find($request->get('id')))->update($request->all())->fresh();

        flash('Empresa atualizada com sucesso')->success()->important();

        return redirect()->route('oracle.dashboard.companies.list');
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

}
