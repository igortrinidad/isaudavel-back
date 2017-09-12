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

class OracleController extends Controller
{

    public function showLogin()
    {
        return view('oracle.auth.login');
    }

}
