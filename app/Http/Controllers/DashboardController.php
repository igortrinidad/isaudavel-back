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
        $company = Company::where('owner_id', Auth::user()->id)->where('id', $id)->with('categories', 'professionals')->first();

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
        $categories = json_decode($request->get('categories'));
        $address = json_decode($request->get('address'));

        //checkboxes
        $address_is_available = $request->get('address_is_available') ? true : false;
        $is_delivery = $request->get('is_delivery') ? true : false;

        $request->merge(['is_delivery' => $is_delivery,'address_is_available' => $address_is_available, 'address' => $address]);


        $company = tap(Company::find($request->get('id')))->update($request->all())->fresh();

        //Check if categories was added
        if(count($categories) > $company->categories()->count() )
        {
            // @todo create a service to manage the subscription / invoice
        }

        //Check if categories was removed
        if(count($categories) < $company->categories()->count() )
        {
            // @todo create a service to manage the subscription / invoice
        }

        //Sync current categories
        $company->categories()->sync($categories);

        flash('Empresa atualizada com sucesso')->success()->important();

        return redirect()->route('professional.dashboard.companies.list');
    }



}
