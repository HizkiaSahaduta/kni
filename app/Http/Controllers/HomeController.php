<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Cache;
use App\User;
use Hash;
use Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        // return view('layouts.maintenance');
        return view('layouts.home');
    }
    // public function index()
    // {

    //     $userid = Session::get('USERNAME');
    //     $groupid = Session::get('GROUPID');
    //     $salesid  = Session::get('SALESID');

    //     $curr = Carbon::now('Asia/Jakarta');
    //     //$curr->setTimezone('UTC +7');
    //     //$curr = Carbon::parse('2020-12-31');

    //     if ($curr->isFuture()){

    //         $curr_month =  $curr->month;
    //         $prev_month = $curr->subMonth()->month;
    //         $year = $curr->subYear()->year;
    //     }
    //     else {

    //         $curr_month =  $curr->month;
    //         $prev_month = $curr->subMonth()->month;
    //         $year =  $curr->year;
    //     }


    //     if ($groupid == "DEVELOPMENT" || $groupid == "KOORDINATOR" || $groupid == "PRU")

    //         {

    //             // Dashboard Order
    //             $order_sum_prev = DB::connection("sqlsrv2")
    //                             ->table('dashboard_order')
    //                             ->selectRaw('cast(sum(total_order) as float) as prev_total')
    //                             ->where('month', '=', $prev_month)
    //                             ->where('year', '=', $year)
    //                             ->first();

    //             $order_sum_curr = DB::connection("sqlsrv2")
    //                             ->table('dashboard_order')
    //                             ->selectRaw('cast(sum(total_order) as float) as curr_total')
    //                             ->where('month', '=', $curr_month)
    //                             ->where('year', '=', $year)
    //                             ->first();

    //             $order_list_sales = DB::connection("sqlsrv2")
    //                             ->select(DB::raw("SELECT a.salesman_id, a.salesman_name, a.month, a.year, b.total_order as prev_order, a.total_order as curr_order,
    //                             (( cast(a.total_order as float) / cast(b.total_order as float)) * 100) - 100 as prosentase
    //                             FROM dashboard_order a
    //                             LEFT OUTER JOIN dashboard_order b
    //                             ON a.salesman_id = b.salesman_id
    //                             where a.month = $curr_month and a.year = $year and b.month = $prev_month and b.year = $year"));

    //             $order_graph = DB::connection("sqlsrv2")
    //                             ->select(DB::raw("SELECT a.salesman_id, a.salesman_name, a.month, a.year, cast(b.total_order as float) as prev_order,
    //                             cast(a.total_order as float) as curr_order
    //                             FROM dashboard_order a
    //                             LEFT OUTER JOIN dashboard_order b
    //                             ON a.salesman_id = b.salesman_id
    //                             where a.month = $curr_month and a.year = $year and b.month = $prev_month and b.year = $year"));


    //             $order_prev_total = $order_sum_prev->prev_total;
    //             $order_curr_total = $order_sum_curr->curr_total;
    //             $order_hitung = (($order_curr_total / $order_prev_total) * 100) - 100;

    //             if ($order_hitung >= 0) {

    //                     $order_cek = "up";

    //             }
    //             else {

    //                     $order_cek = "down";

    //             }

    //             $order_kategori = array();
    //             $order_prev = array();
    //             $order_curr = array();

    //             foreach ($order_graph as $order_graph) {

    //                 $order_kategori[] = LTRIM(RTRIM($order_graph->salesman_name));
    //                 $order_prev[] = $order_graph->prev_order;
    //                 $order_curr[] = $order_graph->curr_order;

    //             }

    //             $order_kategori = join("','",$order_kategori);
    //             $order_prev = join(",",$order_prev);
    //             $order_curr = join(",",$order_curr);
    //             //echo $order_kategori."<br>".$order_prev."<br>".$order_curr;


    //             return view('layouts.home',['year' => $year, 'curr_month' => $curr_month, 'prev_month' => $prev_month,
    //             'order_prev_total' => $order_prev_total, 'order_curr_total' => $order_curr_total, 'order_hitung' => $order_hitung,
    //             'cek' => $order_cek, 'order_list_sales' => $order_list_sales, 'order_kategori' => $order_kategori,
    //             'order_prev' => $order_prev, 'order_curr' => $order_curr]);

    //             //echo number_format($prev_total, 2,',' , '.')."<br>".number_format($curr_total, 2,',' , '.')."<br>".round($hitung, 2)."<br>".$cek;


    //         }


    //     else if ($groupid == "SALES")

    //         {

    //             $order_sum_prev = DB::connection("sqlsrv2")
    //                             ->table('dashboard_order')
    //                             ->selectRaw('cast(sum(total_order) as float) as prev_total')
    //                             ->where('month', '=', $prev_month)
    //                             ->where('year', '=', $year)
    //                             ->where('salesman_id', '=', $salesid)
    //                             ->first();

    //             $order_sum_curr = DB::connection("sqlsrv2")
    //                             ->table('dashboard_order')
    //                             ->selectRaw('cast(sum(total_order) as float) as curr_total')
    //                             ->where('month', '=', $curr_month)
    //                             ->where('year', '=', $year)
    //                             ->where('salesman_id', '=', $salesid)
    //                             ->first();


    //             $order_list_sales = DB::connection("sqlsrv2")
    //                             ->select(DB::raw("SELECT a.salesman_id, a.salesman_name, a.month, a.year, b.total_order as prev_order, a.total_order as curr_order,
    //                             (( cast(a.total_order as float) / cast(b.total_order as float)) * 100) - 100 as prosentase
    //                             FROM dashboard_order a
    //                             LEFT OUTER JOIN dashboard_order b
    //                             ON a.salesman_id = b.salesman_id
    //                             where a.month = $curr_month and a.year = $year and b.month = $prev_month and b.year = $year
    //                             and a.salesman_id='$salesid'"));


    //             $order_graph = DB::connection("sqlsrv2")
    //                             ->select(DB::raw("SELECT distinct a.salesman_id, a.salesman_name,
    //                             a.month as m1, cast(a.total_order as float) as t1,
    //                             b.month as m2, cast(b.total_order as float) as t2,
    //                             c.month as m3, cast(c.total_order as float) as t3
    //                             FROM dashboard_order a
    //                             LEFT OUTER JOIN dashboard_order b
    //                             ON a.salesman_id = b.salesman_id
    //                             LEFT OUTER JOIN dashboard_order c
    //                             ON b.salesman_id = c.salesman_id
    //                             LEFT OUTER JOIN dashboard_order d
    //                             ON c.salesman_id = d.salesman_id
    //                             where a.month = $curr_month and a.year = $year and
    //                             b.month = (a.month)-1 and b.year = $year and
    //                             c.month = (a.month)-2 and c.year = $year and a.salesman_id = '$salesid'"));


    //             $order_prev_total = $order_sum_prev->prev_total;
    //             $order_curr_total = $order_sum_curr->curr_total;
    //             $order_hitung = (($order_curr_total / $order_prev_total) * 100) - 100;

    //             if ($order_hitung >= 0) {

    //                     $order_cek = "up";

    //             }
    //             else {

    //                     $order_cek = "down";

    //             }

    //             $order_kategori = array();
    //             $order_series = array();

    //             foreach ($order_graph as $order_graph) {

    //                 $order_kategori[] = date("F", mktime(0, 0, 0, $order_graph->m3, 1));
    //                 $order_kategori[] = date("F", mktime(0, 0, 0, $order_graph->m2, 1));
    //                 $order_kategori[] = date("F", mktime(0, 0, 0, $order_graph->m1, 1));
    //                 $order_series[] = $order_graph->t3;
    //                 $order_series[] = $order_graph->t2;
    //                 $order_series[] = $order_graph->t1;

    //             }

    //             $order_kategori = join("','",$order_kategori);
    //             $order_series = join(",",$order_series);



    //             //dd($order_list_sales);

    //             return view('layouts.home',['year' => $year, 'curr_month' => $curr_month, 'prev_month' => $prev_month,
    //             'order_prev_total' => $order_prev_total, 'order_curr_total' => $order_curr_total, 'order_hitung' => $order_hitung,
    //             'cek' => $order_cek, 'order_list_sales' => $order_list_sales, 'order_kategori' => $order_kategori,
    //             'order_series' => $order_series ]);

    //         }

    //     else
    //     {
    //         return redirect('home')->with("alert", "You are not allowed to view this page");
    //     }

    //     //echo $groupid;

    // }

    public function ChangeDefaultPassword(Request $request)
    {

        $newPass = $request->password;

        if($newPass != 'kencana123')
        {
            DB::connection("sqlsrv")
                ->table('users')
                ->where('username', '=', Auth::User()->username)
                ->update(['password' => Hash::make($newPass), 'plain_password' => $newPass]);
        }
        else
        {
            return view('layouts.ChangeDefaultPassword')->with('alert','Cannot use default password as new password.');
        }

    }

    public function ChangeAll()
    {
        $user = DB::table('sec_user_sunrise')
        ->select('user_id2')
        ->pluck('user_id2');

        foreach($user as $user) {
            $password = DB::table('sec_user_sunrise')
                            ->select('user_pass')
                            ->where('user_id2', '=', $user)
                            ->value('user_pass');

            $update = DB::table('sec_user_sunrise')
            ->where('user_id2', '=', $user)
            ->update(['username' => $user, 'password' => Hash::make($password)]);
        }

        return view('layouts.home');
    }

    public static function getMonthName($monthNumber)
    {

        return date("F", mktime(0, 0, 0, $monthNumber, 1));

    }

}
