<?php

namespace App\Http\Controllers;

use App\Http\Services\InvoiceServices;
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

class DashboardController extends Controller
{
    /**
     * @var InvoiceServices
     */
    private $invoiceServices;

    /**
     * DashboardController constructor.
     * @param InvoiceServices $invoiceServices
     */
    public function __construct(InvoiceServices $invoiceServices)
    {
        $this->invoiceServices = $invoiceServices;
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
        $categories = json_decode($request->get('categories'));
        $company_professionals = json_decode($request->get('company_professionals'));
        $professionals = (Integer)$request->get('professionals');

        $company = Company::find($request->get('id'));

        $subscription = CompanySubscription::where('company_id', $request->get('id'))->first();
        $last_invoice = CompanyInvoice::where('subscription_id', $subscription->id)->latest()->first();

        //Check for changes
        if (count($categories) != $subscription->categories || $professionals != $subscription->professionals) {

            $new_items = [];
            $subscription_total = $subscription->total;
            $new_total = 0;

            //category added
            if (count($categories) > $subscription->categories) {
                $new_items = [];
                $start = Carbon::now();
                $period = $start->diffInDays(Carbon::createFromFormat('d/m/Y', $subscription->expire_at));

                $new_item = [
                    'item' => 'categories',
                    'total' => (37.9 / 30) * $period * (count($categories) - $subscription->categories),
                    'quantity' => count($categories) - $subscription->categories,
                    'reference' => 'Referente ao período de ' . Carbon::now()->format('d/m/Y') . ' à ' . $last_invoice->expire_at,
                    'is_partial' => true,
                    'description' => 'Categoria(s) adicionais da empresa',
                ];

                $new_items[] = $new_item;

            }

            //category removed
            if (count($categories) < $subscription->categories) {

                $new_total += -37.9 * ($subscription->categories - count($categories));

            }

            //professional added
            if ($professionals > $subscription->professionals) {
                count($new_items) ? $new_items : [];
                $start = Carbon::now();
                $period = $start->diffInDays(Carbon::createFromFormat('d/m/Y', $subscription->expire_at));

                $new_item = [
                    'item' => 'professionals',
                    'total' => (17.9 / 30) * $period * ($professionals - $subscription->professionals),
                    'quantity' => $professionals - $subscription->professionals,
                    'reference' => 'Referente ao período de ' . Carbon::now()->format('d/m/Y') . ' à ' . $last_invoice->expire_at,
                    'is_partial' => true,
                    'description' => 'Profissionais adicionais da empresa',
                ];

                $new_items[] = $new_item;
            }

            //professional removed
            if ($professionals < $subscription->professionals) {

                $new_total += -17.9 * ($subscription->professionals - $professionals);
            }

            if(count($new_items)) {

                foreach ($new_items as $item) {
                    $new_total += $item['total'];
                }

                $total = $subscription_total + $new_total;

                $invoice_history = [
                    [
                        'full_name' =>'Sistema iSaudavel',
                        'action' => 'invoice-created',
                        'label' => 'Fatura gerada',
                        'date' => Carbon::now()->format('Y-m-d H:i:s')
                    ]
                ];

                $new_invoice = CompanyInvoice::create([
                    'company_id' => $subscription->company_id,
                    'subscription_id' => $subscription->id,
                    'total' => round($new_total,2),
                    'expire_at' => $subscription->expire_at,
                    'items' => $new_items,
                    'history'=> $invoice_history
                ]);

                $this->invoiceServices->SendNewInvoiceMail($new_invoice->load('company.owner'));
            }

            if(!count($new_items)){
                $total = $subscription_total - abs($new_total);
            }


            SubscriptionHistory::create(
                [
                    'company_id' => $subscription->company_id,
                    'subscription_id' => $subscription->id,
                    'action' => 'subscription-update',
                    'description' => 'Assinatura atualizada',
                    'professionals_old_value' => $subscription->professionals,
                    'professionals_new_value' => $professionals,
                    'categories_old_value' => $subscription->categories,
                    'categories_new_value' => count($categories),
                    'total_old_value' => $subscription->total,
                    'total_new_value' => round($total,2),
                    'user_id' => \Auth::user()->id,
                    'user_type' => get_class(\Auth::user())
                ]
            );

            $subscription->categories = count($categories);
            $subscription->professionals = $professionals;
            $subscription->total = round($total,2);
            $subscription->save();

            //Sync current categories and $professionals
            $company->categories()->sync($categories);

            if(count($company_professionals)){
                $company->professionals()->sync($company_professionals);
            }

            flash('Assinatura atualizada com sucesso')->success()->important();

            return redirect()->back();

        }

        flash('Não houve alteração na assinatura.')->info()->important();

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
