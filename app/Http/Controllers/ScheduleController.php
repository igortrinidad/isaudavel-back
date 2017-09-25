<?php

namespace App\Http\Controllers;

use App\Models\CategoryCalendarSetting;
use App\Models\Company;
use App\Models\CompanyInvoice;
use App\Models\ProfessionalCalendarSetting;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $schedules = Schedule::where('company_id', $id)->with(['client', 'plan'])->get();

        return response()->json(['schedules' => $schedules]);
    }

    /**
     * Display a listing of schedules for calendar by month.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function forCalendar(Request $request)
    {
        $calendar_settings = ProfessionalCalendarSetting::where('company_id', $request->get('company_id'))
            ->where('category_id', $request->get('category_id'))
            ->with('professional')->get();

        $category_calendar_settings = CategoryCalendarSetting::where('company_id', $request->get('company_id'))
            ->where('category_id', $request->get('category_id'))->select('advance_schedule','advance_reschedule', 'cancel_schedule', 'is_professional_scheduled' )->first();

        foreach($calendar_settings as $calendar_setting){

            $calendar_setting->professional->makeHidden(['companies','categories','blank_password']);

            $schedules = Schedule::where('company_id', $request->get('company_id'))
                ->where('category_id', $request->get('category_id'))
                ->where('professional_id', $calendar_setting->professional_id)
                ->whereBetween('date', [$request->get('start'), $request->get('end')])
                ->with('subscription')
                ->orderBy('date')
                ->orderBy('time')
                ->get();

            foreach($schedules as $schedule){

                $schedule->professional->makeHidden(['companies','categories','blank_password']);

                $schedule->setAttribute('client', $schedule->subscription->client);

                $schedule->makeHidden(['subscription', 'professional']);
            }

            $calendar_setting->setAttribute('schedules', $schedules);

        }
        
        return response()->json(['schedules' => $calendar_settings, 'category_calendar_settings'=>  $category_calendar_settings]);
    }

    /**
     * Display a listing of schedules for calendar by month.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function professionalCalendar(Request $request)
    {
        $calendar_settings = ProfessionalCalendarSetting::where('professional_id', \Auth::user()->id)
            ->with('company')->get();

        foreach($calendar_settings as $calendar_setting){

            $calendar_setting->makeHidden(['professional']);

            $schedules = Schedule::where('company_id', $calendar_setting->company_id)
                ->where('category_id', $calendar_setting->category_id)
                ->where('professional_id', $calendar_setting->professional_id)
                ->whereBetween('date', [$request->get('start'), $request->get('end')])
                ->with('subscription')
                ->orderBy('date')
                ->orderBy('time')
                ->get();

            foreach($schedules as $schedule){

                $schedule->professional->makeHidden(['companies','categories','blank_password']);

                $schedule->setAttribute('client', $schedule->subscription->client);

                $schedule->makeHidden(['subscription', 'professional']);
            }

            $calendar_setting->setAttribute('schedules', $schedules);

        }

        return response()->json(['schedules' => $calendar_settings]);
    }

    /**
     * Display a listing of schedules for calendar by month.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function clientCalendar(Request $request)
    {

        $client_schedules = Schedule::whereHas('subscription', function ($query) {
            $query->where('client_id', \Auth::user()->id);
        })->whereBetween('date', [$request->get('start'), $request->get('end')])
            ->with('category')
            ->get()
            ->groupBy('company_id');

            $companies = [];
            foreach($client_schedules as $key => $schedules)
            {
                foreach ($schedules as $schedule){
                    $schedule->professional->makeHidden(['companies','categories','blank_password', 'password', 'remember_token']);

                    $category_calendar_settings = CategoryCalendarSetting::where('company_id', $key)
                        ->where('category_id', $schedule->category_id)
                        ->select('advance_schedule','advance_reschedule', 'cancel_schedule', 'is_professional_scheduled' )
                        ->first();
                    $schedule->setAttribute('category_calendar_settings', $category_calendar_settings);
                }

                $company = Company::find($key);

                $company->setAttribute('schedules', $schedules);

                $companies[] = $company;
            }

        return response()->json(['schedules' => $companies]);
    }


    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $schedule = Schedule::find($id);

        return response()->json(['schedule' => $schedule]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $schedule = Schedule::create($request->all());

        return response()->json([
            'message' => 'Schedule created.',
            'schedule' => $schedule->fresh()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $schedule = tap(Schedule::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Schedule updated.',
            'schedule' => $schedule->load('category', 'professional')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reschedule(Request $request)
    {
        $request->merge(['is_rescheduled' => true, 'reschedule_by' => \Auth::user()->full_name, 'reschedule_at' => Carbon::now()]);

        $old_schedule = Schedule::find($request->get('id'));
        $schedule = tap(Schedule::find($request->get('id')))->update($request->all())->fresh();

        $schedule->setAttribute('client', $schedule->subscription->client);

        $calendar_settings = ProfessionalCalendarSetting::where('company_id', $request->get('company_id'))
            ->where('category_id', $request->get('category_id'))
            ->first();

        $schedule->setAttribute('professional_workdays', $calendar_settings->workdays);

        $schedule->professional->makeHidden(['companies', 'categories', 'blank_password', 'password', 'remember_token']);

        $schedule->makeHidden(['subscription']);


        //Report email
        $data = [];
        $data['align'] = 'center';

        $data['messageTitle'] = '<h4>Alteração de horário</h4>';
        $data['messageOne'] = 'O usuário <b>'. \Auth::user()->full_name . '</b> acabou de alterar seu horário de  <b>' .$schedule->category->name . '</b> marcado anteriormente para ' . $old_schedule->date . ' ' . $old_schedule->time . '.<hr>
        <p><b>Novo horário</b></p>
        <b>' .$schedule->date . ' ' . $schedule->time . '</b>';

        $data['messageTwo'] = 'Acesse online em https://isaudavel.com ou baixe o aplicativo para Android e iOS (Apple)';

        $data['messageSubject'] = 'Alteração de horário';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data, $schedule){
            $message->from('no-reply@isaudavel.com', 'iSaudavel App');
            $message->to($schedule->client->email, $schedule->client->full_name)->subject($data['messageSubject']);
            $message->cc($schedule->professional->email, $schedule->professional->full_name)->subject($data['messageSubject']);
        });

        return response()->json([
            'message' => 'Rescheduled.',
            'schedule' => $schedule->load('category')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroyed = Schedule::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Schedule destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Schedule not found.',
        ], 404);

    }



    /**
     * confirm the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function confirm(Request $request)
    {
        $request->merge(['is_confirmed' => true, 'confirmed_by' => \Auth::user()->full_name, 'confirmed_at' => Carbon::now()]);

        $schedule = tap(Schedule::find($request->get('id')))->update($request->all())->fresh();

        $schedule->setAttribute('client', $schedule->subscription->client);

        return response()->json([
            'message' => 'Confirmed.',
            'schedule' => $schedule->load('category')
        ]);
    }

    /**
     * Cancel the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request)
    {
        $request->merge(['is_canceled' => true, 'canceled_by' => \Auth::user()->full_name, 'canceled_at' => Carbon::now()]);

        $old_schedule = Schedule::find($request->get('id'));
        $schedule = tap(Schedule::find($request->get('id')))->update($request->all())->fresh();

        $schedule->setAttribute('client', $schedule->subscription->client);

        $calendar_settings = ProfessionalCalendarSetting::where('company_id', $request->get('company_id'))
            ->where('category_id', $request->get('category_id'))
            ->first();

        $schedule->setAttribute('professional_workdays', $calendar_settings->workdays);

        $schedule->professional->makeHidden(['companies', 'categories', 'blank_password', 'password', 'remember_token']);

        $schedule->makeHidden(['subscription']);


        //Report email
        $data = [];
        $data['align'] = 'center';

        $data['messageTitle'] = '<h4>Cancelamento de horário</h4>';
        $data['messageOne'] = 'O horário de ' .$schedule->category->name .  ' marcado para ' .$old_schedule->date . ' ' . $old_schedule->time . ' foi cancelado por '. \Auth::user()->full_name.'.';

        $data['messageSubject'] = 'Cancelamento de horário';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data, $schedule){
            $message->from('no-reply@isaudavel.com', 'iSaudavel App');
            $message->to($schedule->client->email, $schedule->client->full_name)->subject($data['messageSubject']);
            if(\Auth::user()->id != $schedule->client->id){
                $message->cc(\Auth::user()->email, \Auth::user()->full_name, $schedule->professional->email, $schedule->professional->full_name)->subject($data['messageSubject']);
            }
        });

        return response()->json([
            'message' => 'Canceled.',
            'schedule' => $schedule->load('category')
        ]);
    }

    /**
     * Remove all schedules from invoice.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroyAll(Request $request)
    {
        $destroyeds = Schedule::where('invoice_id',$request->get('invoice_id'))->delete();

        if($destroyeds){
            return response()->json([
                'message' => 'Schedules destroyed.',
            ]);
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function byInvoice($id)
    {
        $schedules = Schedule::where('invoice_id', $id)->get();

        return response()->json(['schedules' => $schedules]);
    }

}
