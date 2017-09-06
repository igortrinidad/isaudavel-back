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
        $companies = Auth::user()->companies;

        return view('dashboard.companies.index', compact('companies'));
    }



}
