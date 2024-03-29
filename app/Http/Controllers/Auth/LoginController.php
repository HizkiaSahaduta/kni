<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Session;
use DB;
use Carbon\Carbon;
use Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }


    public function login(Request $request)
    {

        $remember = ($request->has('remember')) ? true : false;

        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        if(Auth::attempt(['username' => $request->username, 'password' => $request->password]))
        {
            $userStatus = Auth::User()->active_flag;
            $passCheck = Auth::User()->password;

            if (Hash::check($request->username, $passCheck))
            {
                Auth::logout();
                Session::put('USERNAME', $request->username);
                return redirect('ChangeDefaultPassword');
            }
            else
            {
                if($userStatus == 'Y')
                {
                    $datetime = Carbon::now();
                    $updateLastActive = DB::table('sec_user')
                                        ->where('username', '=', Auth::User()->username)
                                        ->update(['last_active' => $datetime]);

                    /* $clientIP = $_SERVER['REMOTE_ADDR']; */
                    $clientIP = request()->ip();

                    $groupid = DB::table('sec_group')
                                ->select('group_id')
                                ->where('appl_id','=', 'KNIWEB')
                                ->Where('user_id2', '=', Auth::User()->username)
                                ->Value('group_id');

                     $name1 = DB::table('sec_user')
                                ->select('name1')
                                ->Where('user_id2', '=', Auth::User()->username)
                                ->Value('name1');

                     $name2 = DB::table('sec_user')
                                ->select('name2')
                                ->Where('user_id2', '=', Auth::User()->username)
                                ->Value('name2');

                    $name3 = DB::table('sec_user')
                                ->select('name3')
                                ->Where('user_id2', '=', Auth::User()->username)
                                ->Value('name3');

                    $getEnvMnu = DB::table('sec_right')
                                ->select('menu_id')
                                ->where('group_id','=', $groupid)
                                ->where('active_flag','=', 'Y')
                                ->get();

                    foreach($getEnvMnu as $getEnvMnu) {
                        Session::put($getEnvMnu->menu_id, $getEnvMnu->menu_id);
                    }

                    Session::put('GROUPID', $groupid);
                    Session::put('NAME1', $name1);
                    Session::put('NAME2', $name2);
                    Session::put('NAME3', $name3);
                    Session::put('USERNAME', Auth::User()->username);
                    Session::put('PASSWORD', $request->password);
                    Session::put('USERIP', $clientIP);


                    if($groupid != 'GUEST')
                    {

                        // $salesid = DB::table('sec_env_conf')
                        //     ->select('var_value')
                        //     ->where('appl_id','=', 'KENCANAWEB')
                        //     ->where('var_id', '=', 'SALESID')
                        //     ->where('user_id2', '=', Auth::User()->username)
                        //     ->Value('var_value');

                        // $officeid = DB::table('sec_env_conf')
                        //     ->select('var_value')
                        //     ->where('appl_id','=', 'KENCANAWEB')
                        //     ->where('var_id', '=', 'OFFICEID')
                        //     ->Where('user_id2', '=', Auth::User()->username)
                        //     ->Value('var_value');

                        // $officename = DB::connection("sqlsrv2")
                        //     ->table('branch_office')
                        //     ->select('office')
                        //     ->where('office_id','=', $officeid)
                        //     ->Value('var_value');

                        // $rm = DB::connection("sqlsrv2")
                        //     ->table('branch_office')
                        //     ->select('region')
                        //     ->distinct()
                        //     ->where('rm','=', $salesid)
                        //     ->Value('var_value');

                        // Session::put('OFFICEID', $officeid);
                        // Session::put('OFFICENAME', $officename);

                        //     if($rm)
                        //     {
                        //         Session::put('GROUPID', 'REGION');
                        //         Session::put('REGIONID', $rm);
                        //     }
                        //     else
                        //     {
                        //         Session::put('GROUPID', $groupid);
                        //     }

                        // Session::put('SALESID', $salesid);
                        // Session::put('ACTIVE_FLAG', Auth::User()->active_flag);


                        // ####### API AUTH #######

                        $userid = Auth::User()->username;
                        $curl_getUserExist = curl_init();
                        $curl_register = curl_init();
                        $curl_login = curl_init();

                        curl_setopt_array($curl_getUserExist, array(
                            CURLOPT_URL => "https://webservice.kencana.org/api/getUserExist?email=".$userid."@".$userid.".com",
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_TIMEOUT => 30000,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "GET",
                            CURLOPT_HTTPHEADER => array(
                                // Set Here Your Requesred Headers
                                'Content-Type: application/json',
                            ),
                        ));
                        $response_getUserExist = curl_exec($curl_getUserExist);
                        $err_getUserExist = curl_error($curl_getUserExist);
                        curl_close($curl_getUserExist);
                
                        if ($err_getUserExist) {
                            return redirect()->route('home')->with('error','UserExist#:' . $err_getUserExist);
                        }
                        else {
                            $response_getUserExist = json_decode($response_getUserExist);
                            $count = $response_getUserExist->message;
                
                            if ($count < 1){
                
                                $data = [
                                    'name' => $userid,
                                    'email' => $userid.'@'.$userid.'.com',
                                    'password' => 'GeraltOfRivia1993',
                                    'password_confirmation' => 'GeraltOfRivia1993',
                                    'type' => 1,
                                ];
                
                                curl_setopt_array($curl_register, array(
                                    CURLOPT_URL => "https://webservice.kencana.org/api/register",
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => "",
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 30000,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => "POST",
                                    CURLOPT_POSTFIELDS => json_encode($data),
                                    CURLOPT_HTTPHEADER => array(
                                        // Set here requred headers
                                        "accept: */*",
                                        "accept-language: en-US,en;q=0.8",
                                        "content-type: application/json",
                                    ),
                                ));
                
                                $response_register = curl_exec($curl_register);
                                $err_register = curl_error($curl_register);
                
                                curl_close($curl_register);
                
                                if ($err_register) {
                                    return redirect()->route('home')->with('error','Register#:' . $err_register);
                                } else {
                                    $response_register = json_decode($response_register);
                
                                    if (isset($response_register->token)){
                                        Session::put('token', $response_register->token);
                                        //return view('layouts.POConfirm')->with('success','API Authentication Success');
                                        return redirect()->route('home')->with('success','Voila! Succesfully login');
                                    }
                                    else {
                                        $errors = '';
                                        foreach($response_register->errors as $a)
                                        {
                
                                            $a = str_replace(".", '\n', $a);
                                            $errors .= $a;
                
                                        }
                                        return redirect()->route('home')->with('error','RegisterAttempt#:' . $errors);
                                    }
                                }
                            }
                            else {
                
                                $data = [
                                    'email' => $userid.'@'.$userid.'.com',
                                    'password' => 'GeraltOfRivia1993',
                                ];
                
                                curl_setopt_array($curl_login, array(
                                    CURLOPT_URL => "https://webservice.kencana.org/api/login",
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => "",
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 30000,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => "POST",
                                    CURLOPT_POSTFIELDS => json_encode($data),
                                    CURLOPT_HTTPHEADER => array(
                                        // Set here requred headers
                                        "accept: */*",
                                        "accept-language: en-US,en;q=0.8",
                                        "content-type: application/json",
                                    ),
                                ));
                
                                $response_login= curl_exec($curl_login);
                                $err_login = curl_error($curl_login);
                
                                curl_close($curl_login);
                
                                if ($err_login) {
                                    return redirect()->route('home')->with('error','Login#:' . $err_login);
                                } else {
                                    $response_login = json_decode($response_login);
                
                                    if (isset($response_login->token)){
                                        Session::put('token', $response_login->token);
                                        //return view('layouts.POConfirm')->with('success','API Authentication Success');
                                        return redirect()->route('home')->with('success','Voila! Succesfully login');
                                    }
                                    else {
                                        return redirect()->route('home')->with('error','LoginAttempt#:' . $response_login->message);
                                    }
                
                                }
                
                            }
                        }
                    }
                    else
                    {

                        return redirect()->route('home2');
                    }

                }
                else
                {
                    Auth::logout();
                    Session::flush();
                    return redirect(url('login'))->withInput()->with('alert','Your ID is blocked. Please contact our admin');
                }
            }
        }else
        {
            return redirect()->route('login')->with('alert','Sorry, your username and password is incorrect. Please try again.');
        }
    }

}
