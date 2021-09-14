<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        return view('payment')->with('paylist',$value);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Set the consumer key and consumer secret as follows
        $pubKey = env('PUBLIC_KEY');
        $mpesa = new \Karson\MpesaPhpSdk\Mpesa();
        $mpesa->setApiKey(env('API_KEY'));
        $mpesa->setPublicKey($pubKey);
        $mpesa->setEnv('test'); // 'live' production environment

        #Generates the reference id randomically
        $random_number = bin2hex(random_bytes(5));
        #Concatenating the suffix 258 to the customer number
        $contact = '258' . $request->msisdn;
        #Setting the ammount to be transfered
        $amount = $request->amount;

        //This creates transaction between an M-Pesa short code to a phone number registered on M-Pesa.
        $result = $mpesa->c2b('MK6p7u', $contact, $amount, $random_number, '171717');
        $TransactionHistory = collect([]);

        //Decoding the Mpesa Json response
        $array = json_decode(json_encode($result), true);
        $get = $array['response'];


        #Verifying the status of operation
        #Note: se o output_ResponseCode for igual a INS-0 a transacao foi com sucesso
        if ($get["output_ResponseCode"] == "INS-0") {

            # Caso queira cada variavel do response pode capturar dessa forma
                //echo $get['output_ResponseCode'];
                //echo $get['output_ResponseDesc'];
                //echo $get['output_TransactionID'];
                //echo $get['output_ConversationID'];
                //echo $get['output_ThirdPartyReference'];
            #===========================================

            return $array;
        } else {

            return ($array);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
