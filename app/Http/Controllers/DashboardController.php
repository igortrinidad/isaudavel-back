<?php

namespace App\Http\Controllers;

use App\Models\CompanyInvoice;
use App\Models\CompanySubscription;
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
        $company = Company::where('owner_id', Auth::user()->id)->where('id', $id)->with('categories', 'professionals', 'subscription')->first();

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

            foreach($last_invoice->items as $item){
                $new_items[] = $item;
            }
            //category added
            if (count($categories) > $subscription->categories) {
                $start = Carbon::createFromFormat('d/m/Y', $subscription->start_at);
                $period = $start->diffInDays(Carbon::createFromFormat('d/m/Y', $last_invoice->expire_at));

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
                $start = Carbon::createFromFormat('d/m/Y', $subscription->start_at);
                $period = $start->diffInDays(Carbon::createFromFormat('d/m/Y', $last_invoice->expire_at));

                $new_item = [
                    'item' => 'categories',
                    'total' => ((37.9 / 30) * ($period-30)) - 37.9 * ($subscription->categories - count($categories)),
                    'quantity' => $subscription->categories - count($categories),
                    'reference' => 'Referente ao período de ' . Carbon::now()->format('d/m/Y') . ' à ' . $last_invoice->expire_at,
                    'is_partial' => true,
                    'description' => 'Remoção de categoria(s) da empresa',
                ];
                $new_items[] = $new_item;

                //dd($new_item);
            }
            //professional added

            if ($professionals > $subscription->professionals) {

                $start = Carbon::createFromFormat('d/m/Y', $subscription->start_at);
                $period = $start->diffInDays(Carbon::createFromFormat('d/m/Y', $last_invoice->expire_at));

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
                $start = Carbon::createFromFormat('d/m/Y', $subscription->start_at);
                $period = $start->diffInDays(Carbon::createFromFormat('d/m/Y', $last_invoice->expire_at));

                $new_item = [
                    'item' => 'professionals',
                    'total' => ((17.9 / 30) * (30-$period) - 17.9 ) * ($subscription->professionals - $professionals),
                    'quantity' => $subscription->professionals - $professionals,
                    'reference' => 'Referente ao período de ' . Carbon::now()->format('d/m/Y') . ' à ' . $last_invoice->expire_at,
                    'is_partial' => true,
                    'description' => 'Remoção de profissional da empresa',
                ];
                $new_items[] = $new_item;
            }

            $total = 0;
            foreach ($new_items as $item) {
                $total += $item['total'];
            }

            //dd(count($categories),$professionals, $total, $new_items);

            $subscription->categories = count($categories);
            $subscription->professionals = $professionals;
            $subscription->total = $total;
            $subscription->save();

            $last_invoice->total = $total;
            $last_invoice->items = $new_items;
            $last_invoice->save();

        }

        //Sync current categories and $professionals
        $company->categories()->sync($categories);

        if(count($company_professionals)){
            $company->professionals()->sync($company_professionals);
        }

        flash('Assinatura atualizada com sucesso')->success()->important();

        return redirect()->back();
    }


}
