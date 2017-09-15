<?php

namespace App\Http\Controllers;

use App\Http\Services\SubscriptionServices;
use App\Models\CompanyInvoice;
use Illuminate\Http\Request;
use App\Models\Company;

use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    /**
     * @var SubscriptionServices
     */
    private $subscriptionServices;

    /**
     * DashboardController constructor.
     * @param SubscriptionServices $subscriptionServices
     */
    public function __construct(SubscriptionServices $subscriptionServices)
    {

        $this->subscriptionServices = $subscriptionServices;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('dashboard.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function companiesIndex()
    {
        $companies = Company::where('owner_id', Auth::user()->id)->with('categories', 'professionals')->get();

        return view('dashboard.companies.list', compact('companies'));
    }

    public function companyShow($id)
    {
        $company = Company::where('owner_id', Auth::user()->id)->where('id', $id)->with('categories', 'professionals', 'subscription.histories.user')->first();

        \JavaScript::put(['company' => $company]);

        return view('dashboard.companies.show', compact('company'));
    }

    public function showCompanyEdit($id)
    {
        $company = Company::where('owner_id', Auth::user()->id)->where('id', $id)->with('categories', 'professionals')->first();

        \JavaScript::put(['company' => $company]);

        return view('dashboard.companies.edit', compact('company'));
    }

    public function companyUpate(Request $request)
    {
        $address = json_decode($request->get('address'));

        //checkboxes
        $address_is_available = $request->get('address_is_available') ? true : false;
        $is_delivery = $request->get('is_delivery') ? true : false;

        $request->merge(['is_delivery' => $is_delivery, 'address_is_available' => $address_is_available, 'address' => $address]);

        $company = tap(Company::find($request->get('id')))->update($request->all())->fresh();

        flash('Empresa atualizada com sucesso')->success()->important();

        return redirect()->route('professional.dashboard.companies.list');
    }

    public function subscriptionUpdate(Request $request)
    {
        $this->subscriptionServices->updateSubscription($request);

        return redirect()->back();
    }

    public function invoicesList($id)
    {
       $invoices = CompanyInvoice::where('company_id', $id)->get();

        return view('dashboard.companies.invoices', compact('invoices'));
    }

    public function invoiceShow($company_id, $invoice_id)
    {
        $invoice = CompanyInvoice::with('company.owner')->find($invoice_id);

        return view('dashboard.companies.invoice-show', compact('invoice'));
    }

}
