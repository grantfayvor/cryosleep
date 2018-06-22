<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/14/2018
 * Time: 12:58 PM
 */

namespace App\Services;


use App\Http\Requests\TransactionRequest;
use App\Repositories\cointpayment_log_trxRepository;
use App\Repositories\TransactionRepository;
use CoinPayment;
use Route;
use Illuminate\Http\Request;
use Harrison\CoinPayment\Jobs\webhookProccessJob;
use App\Jobs\coinPaymentCallbackProccedJob;


class TransactionService
{

    private $repository;
    private $coinRepository;
    private $planService;

    public function __construct(TransactionRepository $repository, cointpayment_log_trxRepository $cointpayment_log_trxRepository,
                                TransactionPlanService $planService)
    {
        $this->repository = $repository;
        $this->coinRepository = $cointpayment_log_trxRepository;
        $this->planService = $planService;
    }

    public function generatePaymentURL(TransactionRequest $request)
    {
        $transactionPlan = $this->planService->getById($request->transaction_plan_id);
        if($transactionPlan->max_amount < $request->amount_usd || $transactionPlan->min_amount > $request->amount_usd) {
            return response()->json(['error' => 'Enter a valid amount in the range for that plan'], 422);
        }
        $transaction['amountTotal'] = $request->amount_usd;
        $transaction['note'] = 'Transfer to merchant for trading';
        $transaction['items'][0] = [
            'descriptionItem' => 'Single transfer',
            'priceItem' => $request->amount_usd, // USD
            'qtyItem' => 1,
            'subtotalItem' => $request->amount_usd // USD
        ];
        $transaction['payload'] =  $request->getValues();

        return response()->json(['url' => CoinPayment::url_payload($transaction)]);
    }

    public function generateWithdrawalURL(TransactionRequest $request)
    {
        $transaction['amountTotal'] = $request->amount_usd;
        $transaction['address'] = $request->address;
        $transaction['note'] = 'Transfer to client for payment';
        $transaction['items'][0] = [
            'descriptionItem' => 'Single transfer',
            'priceItem' => $request->amount_usd, // USD
            'qtyItem' => 1,
            'subtotalItem' => $request->amount_usd // USD
        ];
        $transaction['payload'] =  $request->getValues();

        return response()->json(['url' => CoinPayment::withdrawal_url_payload($transaction)]);
    }

    public function ipn_webhook(Request $req)
    {
        $payment = CoinPayment::api_call('get_tx_info', [
            'txid' => $req->result['txn_id']
        ]);

        $transaction = $this->coinRepository->getOneByParam('payment_id', $req->result['txn_id']);
        if ($payment['error'] == 'ok') {
            $data = $payment['result'];

            $saved = [
                'payment_id' => $req->result['txn_id'],
                'payment_address' => $data['payment_address'],
                'coin' => $data['coin'],
                'fiat' => config('coinpayment.default_currency'),
                'status_text' => $data['status_text'],
                'status' => $data['status'],
                'payment_created_at' => date('Y-m-d H:i:s', $data['time_created']),
                'expired' => date('Y-m-d H:i:s', $data['time_expires']),
                'amount' => $data['amountf'],
                'confirms_needed' => empty($req->result['confirms_needed']) ? 0 : $req->result['confirms_needed'],
                'qrcode_url' => empty($req->result['qrcode_url']) ? '' : $req->result['qrcode_url'],
                'status_url' => empty($req->result['status_url']) ? '' : $req->result['status_url'],
                'payload' => empty($req->payload) ? json_encode([]) : json_encode($req->payload),
            ];

            $this->coinRepository->update($transaction->id, $saved);
        }
        $send['request_type'] = 'create_transaction';
        $send['params'] = empty($req->params) ? [] : $req->params;
        $send['payload'] = empty($req->payload) ? [] : $req->payload;
        $send['transaction'] = $payment['error'] == 'ok' ? $payment['result'] : [];
        if (Route::has('coinpayment.webhook')) {
            dispatch(new webhookProccessJob($send));
        }
        dispatch(new coinPaymentCallbackProccedJob($send));
        return $payment;
    }

    public function create(TransactionRequest $request)
    {
        if (!$this->repository->create($request->getValues())) {
            return response()->json(['message' => 'the resource was not created', 'data' => $request->getValues()], 500);
        }
        return response()->json(['message' => 'the resource was successfully created', 'data' => $request->getValues()], 200);
    }

    public function getAll(int $n = null, array $fields = null)
    {
        return $this->repository->getAll($n, $fields);
    }

    public function getById($id)
    {
        return $this->repository->getById($id);
    }

    public function getByParam($param, $value)
    {
        return $this->repository->getByParam($param, $value);
    }

    public function delete($id)
    {
        if (!$this->repository->delete($id)) {
            return response()->json(['message' => 'the resource was not deleted']);
        }
        return response()->json(['message' => 'the resource was successfully deleted']);
    }

    public function getUserTransactions($userId)
    {
        return $this->coinRepository->getUserTransactions($userId);
    }

}