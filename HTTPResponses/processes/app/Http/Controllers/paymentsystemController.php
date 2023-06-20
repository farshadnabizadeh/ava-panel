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
}
