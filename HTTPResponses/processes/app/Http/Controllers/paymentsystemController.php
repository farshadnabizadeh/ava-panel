<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Components\CoinPaymentsAPI;
use App\Models\PaymentSystem;

class paymentsystemController extends Controller
{
    public $Coin;
    public $initSetup;
    public $basicInfo;
    public $username;
    public $scurrency;
    public $recurrency;
    public $request;
    public $result;

    public function __construct()
    {
        $this->Coin = new CoinPaymentsAPI();
    }
    public function init($AMOUNT, $EMAIL)
    {
        $this->initSetup = $this->Coin->Setup(env('PAYMENT_PRIVATE_KEY'), env('PAYMENT_PUBLIC_KEY'));
        $this->basicInfo = $this->Coin->GetBasicProfile();
        $this->username = $this->basicInfo['result']['public_name'];
        $this->scurrency = env('SCURRENCY');
        $this->recurrency = env('RECURRENCY');
        $this->request = [
            'amount' => $AMOUNT,
            'currency1' => $this->scurrency,
            'currency2' => $this->recurrency,
            'buyer_email' => $EMAIL,
            'item' => 'BUYING VPN SERVICE',
            'address' => '',
            'ipn_url' => 'https//www.google.com/status.php',
        ];
        $this->result = $this->Coin->CreateTransaction($this->request);
        if ($this->result['error'] == "ok") {
            $payment = PaymentSystem::create([
                'entered_amount' => $AMOUNT,
                'email' => $EMAIL,
                'amount' => $this->result['result']['amount'],
                'from_currency' => $this->scurrency,
                'to_currency' => $this->recurrency,
                'status' => "initialized",
                'gateway_id' => $this->result['result']['txn_id'],
                'gateway_url' => $this->result['result']['status_url'],

            ]);
            if ($payment) {
                session()->put('txn_id', $this->result['result']['txn_id']);
                return response()->json([
                    'status' => true,
                    'gateway_url' => $this->result['result']['status_url'],
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'data' => "Something went Wrong",
                ]);
            }
        } else {
            print 'Error :' . $this->result['error'] . "\n";
            die();
        }
    }
    public function index(Request $REQUEST)
    {
        return $this->init($REQUEST->AMOUNT, $REQUEST->EMAIL);
    }
    public function getResponse(Request $REQUEST)
    {
        $ERRORS = array();
        $PAYMENT = PaymentSystem::where('gateway_id', session('txn_id'))->first();
        $ORDER_CURRENCY = $PAYMENT->from_currency;
        $ORDER_TOTAL = $PAYMENT->amount;
        if (!$REQUEST->has('ipn_mode') || $REQUEST->input('ipn_mode') == 'hmac') {
            array_push($ERRORS, 'IPN MODE IS NOT HMAC');
        }
        if (!isset($_SERVER['HTTP_HMAC']) || empty($_SERVER['HTTP_HMAC'])) {
            array_push($ERRORS, 'No HMAC SIGNATURE SENT');
        }

        $req = file_get_contents('php://input');
        if ($req === false || empty($req)) {
            array_push($ERRORS, 'ERROR IN READING POST DATA');
        }

        if (!$REQUEST->has('merchant') || $REQUEST->input('merchant') != trim(env('MERCHANT_ID'))) {
            array_push($ERRORS, 'No OR INCORRECT MERCHANT');
        }
        $HMAC = hash_hmac("sha512", $req, trim(env('IPN_SECRET')));
        if (!hash_equals($HMAC, $_SERVER['HTTP_HMAC'])) {
            array_push($ERRORS, 'HMAC SIGNATURE DOES NOT MATCH');
        }
        $amount1 = floatval($REQUEST->input('amount1'));
        $amount2 = floatval($REQUEST->input('amount2'));
        $currency1 = $REQUEST->input('currency1');
        $currency2 = $REQUEST->input('currency2');
        $status = intval($REQUEST->input('status'));
        print_r($_GET);
        return $PAYMENT;
    }
}
