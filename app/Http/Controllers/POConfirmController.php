<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class POConfirmController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        return view('layouts.POConfirm');

    }

    public function getPOHdr (Request $request) {

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

        if ($po_id) {

            $allreq .= '&po_id='.$po_id;

        }

        if ($flag) {

            $allreq .= '&flag='.$flag;

        }

        $url = "https://jalan-api.kencana.org/api/getPOHdr?1=1".$allreq;

        // echo $url;


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
            return view('layouts.POConfirm')->with('error','getPOHdr#:' . $err);
        }
        else {

            $response = json_decode($response);

            return \DataTables::of($response)
                ->editColumn('aprv_flag', function ($data) {
                    if ($data->aprv_flag == "Y") return '<span class="shadow-none badge badge-success"><i class="fa fa-check" aria-hidden="true"></i> Approved</span>';
                    if ($data->aprv_flag == "X") return '<span class="shadow-none badge badge-danger"><i class="fa fa-times" aria-hidden="true"></i> Rejected</span>';
                    return '<span class="shadow-none badge badge-warning"><i class="fa fa-clock-o" aria-hidden="true"></i> Open</span>';
                })
                ->addColumn('Detail', function($data) {

                    if ($data->aprv_flag == "Y")

                        if ($data->sp_id) {
                            return '
                            <a href="javascript:void(0)" data-id="'.$data->po_id.'" class="bs-tooltip viewDtl" data-placement="top" title="Detail" data-toggle="modal" data-target="#detailPO">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text text-primary"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            </a>
                            ';
                        } 
                        else {
                            return '
                            <a href="javascript:void(0)" data-id="'.$data->po_id.'" class="bs-tooltip viewDtl" data-placement="top" title="Detail" data-toggle="modal" data-target="#detailPO">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text text-primary"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            </a>
    
                            <a href="javascript:void(0)" data-id="'.$data->po_id.'" class="bs-tooltip setUnApprove" data-placement="top" title="UnApprove">        
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shuffle"><polyline points="16 3 21 3 21 8"></polyline><line x1="4" y1="20" x2="21" y2="3"></line><polyline points="21 16 21 21 16 21"></polyline><line x1="15" y1="15" x2="21" y2="21"></line><line x1="4" y1="4" x2="9" y2="9"></line></svg>
                            </a>
                            ';
                        }
                        

                    if ($data->aprv_flag == "X")
                    return '
                        <a href="javascript:void(0)" data-id="'.$data->po_id.'" class="bs-tooltip viewDtl" data-placement="top" title="Detail" data-toggle="modal" data-target="#detailPO">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text text-primary"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        </a>
                        ';
                    
                    return '
                    <a href="javascript:void(0)" data-id="'.$data->po_id.'" class="bs-tooltip viewDtl" data-placement="top" title="Detail" data-toggle="modal" data-target="#detailPO">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text text-primary"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    </a>

                    <a href="javascript:void(0)" data-id="'.$data->po_id.'" class="bs-tooltip setApprove" data-placement="top" title="Approve">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle text-success"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </a>

                    <a href="javascript:void(0)" data-id="'.$data->po_id.'" class="bs-tooltip setReject" data-placement="top" title="Reject">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle text-danger"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    </a>
                    ';

                    })
                ->rawColumns(['Detail','aprv_flag'])
                ->make(true);

        }

    }

    public function getSumHdr (Request $request) {

        $token = Session::get('token');
        $dt_start = $request->dt_start;
        $dt_end = $request->dt_end;
        $flag = $request->flag;
        $po_id = $request->po_id;
        $subtotal = "";
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

        if ($po_id) {

            $allreq .= '&po_id='.$po_id;

        }

        if ($flag) {

            $allreq .= '&flag='.$flag;

        }

        $url = "https://jalan-api.kencana.org/api/getSumHdr?1=1".$allreq;

        // echo $url;


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
            return view('layouts.POConfirm')->with('error','getSumHdr#:' . $err);
        }
        else {

            $result = json_decode($response);

            // dd($result);

            if ($result) {

                $subtotal .= '

                <div class="widget-content">
                    <div class="invoice-box">
                        
                        <div class="acc-total-info">
                            <h5>Total Order</h5>
                            <p class="acc-amount">IDR '.$result->net.'</p>
                        </div>
    
                        <div class="inv-detail">      
                            <div class="info-detail-1">
                                <p>Subtotal</p>
                                <p>IDR '.$result->total_po.'</p>
                            </div>                                  
                            <div class="info-detail-1">
                                <p>PPN</p>
                                <p>IDR '.$result->ppn.'</p>
                            </div>
                        </div>
    
                    </div>
                </div>';

                return response()->json(['subtotal' => $subtotal]);

            }

            else {

                $subtotal .= '

                <div class="widget-content">
                    <div class="invoice-box">
                        
                        <div class="acc-total-info">
                            <h5>Total Order</h5>
                            <p class="acc-amount">IDR 0,00</p>
                        </div>
    
                        <div class="inv-detail">      
                            <div class="info-detail-1">
                                <p>Subtotal</p>
                                <p>IDR 0,00</p>
                            </div>                                  
                            <div class="info-detail-1">
                                <p>PPN</p>
                                <p>IDR 0,00</p>
                            </div>
                        </div>
    
                    </div>
                </div>';

                return response()->json(['subtotal' => $subtotal]);
            }

           

            

        }

    }

    public function getPODtl (Request $request) {

        $token = Session::get('token');
        $id = $request->id;

        $url = "https://jalan-api.kencana.org/api/getPODtl?po_id=".$id;

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
            return view('layouts.POConfirm')->with('error','getPODtl#:' . $err);
        }
        else {

            $response = json_decode($response);
            //dd($response);
            return \DataTables::of($response)
                ->make(true);

        }

    }

    public function setApprove (Request $request) {

        $token = Session::get('token');
        $userid = Session::get('USERNAME');
        $id = $request->id;

        $curl = curl_init();

        $data = [
            'userid' => $userid,
            'po_id' => $id,
        ];

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://jalan-api.kencana.org/api/setApprove",
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

    public function setReject (Request $request) {

        $token = Session::get('token');
        $userid = Session::get('USERNAME');
        $id = $request->id;

        $curl = curl_init();

        $data = [
            'userid' => $userid,
            'po_id' => $id,
        ];

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://jalan-api.kencana.org/api/setReject",
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

    public function setUnApprove (Request $request) {

        $token = Session::get('token');
        $userid = Session::get('USERNAME');
        $id = $request->id;

        $curl = curl_init();

        $data = [
            'userid' => $userid,
            'po_id' => $id,
        ];

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://jalan-api.kencana.org/api/setUnApprove",
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
