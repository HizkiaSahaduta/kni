<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class DNConfirmController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        return view('layouts.DNConfirm');

    }

    public function getDNHdr (Request $request) {

        $token = Session::get('token');
        $dt_start = $request->dt_start;
        $dt_end = $request->dt_end;
        $flag = $request->flag;
	    $po_id = $request->po_id;

        $allreq = '';

        if ($dt_start && !$dt_end) {

            $allreq .= '&dt_start='.$request->dt_start;

        }

        if (!$dt_start && $dt_end) {

            $allreq .= '&dt_end='.$request->dt_end;

        }

        if ($dt_start && $dt_end) {

            $allreq .= '&dt_start='.$request->dt_start;
            $allreq .= '&dt_end='.$request->dt_end;

        }

        if ($flag) {

            $allreq .= '&flag='.$flag;

        }

	    if ($po_id) {

            $allreq .= '&po_id='.$po_id;

        }


        $url = "https://jalan-api.kencana.org/api/getDelivHdr?1=1".$allreq;


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                // Set Here Your Requesred Headers
                'Content-Type: application/json',
                "Authorization: Bearer $token",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return view('layouts.DNConfirm')->with('error','getDelivHdr#:' . $err);
        }
        else {

            $response = json_decode($response);
            //dd($response);
            return \DataTables::of($response)
                ->editColumn('stat', function ($data) {
                    if ($data->stat == "Y") return '<span class="shadow-none badge badge-success"><i class="fa fa-check" aria-hidden="true"></i> Confirmed</span>';
                    return '<span class="shadow-none badge badge-warning"><i class="fa fa-clock-o" aria-hidden="true"></i> Open</span>';
                })
                ->addColumn('Detail', function($data) {

                    if ($data->stat == "Y")
                    return '
                        <a href="javascript:void(0)" data-id1="'.$data->deliv_id.'" data-id2="'.$data->order_id.'" class="bs-tooltip viewDelivDtl" data-placement="top" title="Detail" data-toggle="modal" data-target="#detailDN">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text text-primary"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        </a>
                        ';

                    return '
                        <a href="javascript:void(0)" data-id1="'.$data->deliv_id.'" data-id2="'.$data->order_id.'" class="bs-tooltip viewDelivDtl" data-placement="top" title="Detail" data-toggle="modal" data-target="#detailDN">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text text-primary"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        </a>

                        <a href="javascript:void(0)" data-id1="'.$data->deliv_id.'" data-id2="'.$data->order_id.'" class="bs-tooltip setDelivConfirm" data-placement="top" title="Confirm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle text-success"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        </a>
                        ';
                    })
                ->rawColumns(['Detail','stat'])
                ->make(true);


                // <a href="javascript:void(0)" data-id="'.$data->po_id.'" class="bs-tooltip setUnApprove" data-placement="top" title="UnApprove">
                // <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-rotate-ccw text-warning"><polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg>
                // </a>

        }

    }

    public function getDNDtl (Request $request) {

        $token = Session::get('token');
        $deliv_id = $request->deliv_id;
        $order_id = $request->order_id;

        $url = "https://jalan-api.kencana.org/api/getDelivDtl?deliv_id=".$deliv_id."&order_id=".$order_id;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                // Set Here Your Requesred Headers
                'Content-Type: application/json',
                "Authorization: Bearer $token",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return view('layouts.DNConfirm')->with('error','getDNDtl#:' . $err);
        }
        else {

            $response = json_decode($response);
            //dd($response);
            return \DataTables::of($response)
                ->make(true);

        }

    }

    public function setDelivConfirm (Request $request) {

        $token = Session::get('token');
        $userid = Session::get('USERNAME');
        $deliv_id = $request->deliv_id;
        $order_id = $request->order_id;

        $curl = curl_init();

        $data = [
            'userid' => $userid,
            'deliv_id' => $deliv_id,
            'order_id' => $order_id,

        ];

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://jalan-api.kencana.org/api/setDelivConfirm",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                // Set Here Your Requesred Headers
                'Content-Type: application/json',
                "Authorization: Bearer $token",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return response()->json(['response' => 'Error:' . $err]);
        }
        else {

            $response = json_decode($response);
            $response = $response->message;
            return response()->json(['response' => $response]);

        }

    }

}
