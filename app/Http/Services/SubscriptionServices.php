<?php

namespace App\Http\Services;

use App\Models\Company;
use App\Models\CompanyInvoice;
use App\Models\CompanySubscription;
use App\Models\SubscriptionHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubscriptionServices{
    /**
     * @var InvoiceServices
     */
    private $invoiceServices;

    /**
     * SubscriptionServices constructor.
     * @param InvoiceServices $invoiceServices
     */
    public function __construct(InvoiceServices $invoiceServices)
    {
        $this->invoiceServices = $invoiceServices;
    }

    /*
     * Subscription create
     */
    public function createSubscription(Request $request){

        $categories = json_decode($request->get('categories'));
        $professionals = (Integer)$request->get('professionals');
        $is_active = $request->get('is_active') ? true : false;

        $request->merge(['categories' => count($categories), 'professionals' => $professionals, 'is_active' => $is_active]);

        $company = Company::find($request->get('company_id'));

        //Sync current categories
        $company->categories()->sync($categories);

        $subscription = CompanySubscription::create($request->all());

        SubscriptionHistory::create(
            [
                'company_id' => $subscription->company_id,
                'subscription_id' => $subscription->id,
                'action' => 'subscription-create',
                'description' => 'Assinatura criada',
                'professionals_old_value' => 0,
                'professionals_new_value' =>  $subscription->professionals,
                'categories_old_value' => 0,
                'categories_new_value' => $subscription->categories,
                'total_old_value' => 0,
                'total_new_value' => round($subscription->total,2),
                'user_id' => \Auth::user()->id,
                'user_type' => get_class(\Auth::user())
            ]
        );

        $invoice_items = [
            [
                'description' => 'Especialidades da empresa',
                'item' => 'categories',
                'quantity' => $company->categories->count(),
                'total' => ($company->categories->count() * 37.90) ,
                'is_partial' => false,
                'reference' => 'Referente ao período de '.  $subscription->start_at.' à '.$subscription->expire_at
            ],
            [
                'description' => 'Profissionais da empresa',
                'item' => 'professionals',
                'quantity' => $company->professionals->count(),
                'total' => (($company->professionals->count() - 1) * 17.90),
                'is_partial' => false,
                'reference' => 'Referente ao período de '.  $subscription->start_at.' à '.$subscription->expire_at
            ],

        ];

        $invoice_history = [
            [
                'full_name' =>\Auth::user()->full_name,
                'action' => 'invoice-created',
                'label' => 'Fatura gerada',
                'date' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ];

        $invoice = CompanyInvoice::create([
            'company_id' => $company->id,
            'subscription_id' => $subscription->id,
            'total' => $subscription->total,
            'expire_at' => $subscription->expire_at,
            'items' => $invoice_items,
            'history' => $invoice_history,
        ]);

        $this->invoiceServices->SendNewInvoiceMail($invoice->load('company.owner'));

        flash('Assinatura criada com sucesso')->success()->important();

        return redirect()->back();
    }


    /*
     * Subscription update
     */
    public function updateSubscription(Request $request){

        $categories = json_decode($request->get('categories'));
        $company_professionals = json_decode($request->get('company_professionals'));
        $professionals = (Integer)$request->get('professionals');
        $is_active = $request->get('is_active') ? true : false;

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

            //Sync current categories
            $company->categories()->sync($categories);

            if(count($company_professionals)){
                $company->professionals()->sync($company_professionals);
            }

            if($request->has('update_expiration') && $request->get('update_expiration') == 'true'){

                SubscriptionHistory::create(
                    [
                        'company_id' => $subscription->company_id,
                        'subscription_id' => $subscription->id,
                        'action' => 'subscription-update',
                        'description' => 'Vencimento alterado de '. $subscription->expire_at. ' para ' . $request->get('expire_at'),
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

                $subscription->expire_at = $request->get('expire_at');
                $subscription->save();
            }

            flash('Assinatura atualizada com sucesso')->success()->important();

            return redirect()->back();

        }

        if($request->has('update_expiration') && $request->get('update_expiration') == 'true'){

            SubscriptionHistory::create(
                [
                    'company_id' => $subscription->company_id,
                    'subscription_id' => $subscription->id,
                    'action' => 'subscription-update',
                    'description' => 'Vencimento alterado de '. $subscription->expire_at. ' para ' . $request->get('expire_at'),
                    'professionals_old_value' => $subscription->professionals,
                    'professionals_new_value' => $professionals,
                    'categories_old_value' => $subscription->categories,
                    'categories_new_value' => count($categories),
                    'total_old_value' => $subscription->total,
                    'total_new_value' => $subscription->total,
                    'user_id' => \Auth::user()->id,
                    'user_type' => get_class(\Auth::user())
                ]
            );

            $subscription->expire_at = $request->get('expire_at');
            $subscription->save();

            flash('Assinatura atualizada com sucesso')->success()->important();

            return redirect()->back();
        }

        if($subscription->is_active != $is_active){
            SubscriptionHistory::create(
                [
                    'company_id' => $subscription->company_id,
                    'subscription_id' => $subscription->id,
                    'action' => 'subscription-update',
                    'description' => 'Assinatura ' . ($is_active ? 'ativada' : 'desativada'),
                    'professionals_old_value' => $subscription->professionals,
                    'professionals_new_value' => $professionals,
                    'categories_old_value' => $subscription->categories,
                    'categories_new_value' => count($categories),
                    'total_old_value' => $subscription->total,
                    'total_new_value' => $subscription->total,
                    'user_id' => \Auth::user()->id,
                    'user_type' => get_class(\Auth::user())
                ]
            );

            $subscription->is_active = $is_active;
            $subscription->save();

            flash('Assinatura atualizada com sucesso')->success()->important();

            return redirect()->back();
        }

        flash('Não houve alteração na assinatura.')->info()->important();

        return redirect()->back();
    }
}