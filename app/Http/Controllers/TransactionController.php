<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/15/2018
 * Time: 12:20 PM
 */

namespace App\Http\Controllers;


use App\Http\Requests\TransactionRequest;
use App\Services\TransactionService;
use App\Services\TransactionTypeService;
use App\Services\UserService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    private $service;
    private $transactionTypeService;
    private $userService;

    public function __construct(TransactionService $service, TransactionTypeService $typeService, UserService $userService)
    {
        $this->service = $service;
        $this->transactionTypeService = $typeService;
        $this->userService = $userService;
    }

    public function testTransactionWithDummyData(Request $request)
    {
        return $this->service->testTransactionWithDummyData($request);
    }

    public function generatePaymentURL(TransactionRequest $request)
    {
        $transactionType = $this->transactionTypeService->getOneByParam('name', 'Deposit');
        $request->transaction_type_id = $transactionType->id;
        return $this->service->generatePaymentURL($request);
    }

    public function generateWithdrawalURL(TransactionRequest $request)
    {
        $transactionType = $this->transactionTypeService->getOneByParam('name', 'Withdrawal');
        $request->transaction_type_id = $transactionType->id;
        return $this->service->generateWithdrawalURL($request);
    }

    public function create(TransactionRequest $request)
    {
        return $this->service->create($request);
    }

    public function ipn_webhook(Request $request)
    {
        return $this->service->ipn_webhook($request);
    }

    public function getAll(Request $request)
    {
        $n = $request->input('n') ?: null;
        $fields = $request->input('fields') ? explode(',', $request->input('fields')) : null;
        return $this->service->getAll($n, $fields);
    }

    public function getAllCoinTransactions()
    {
        return $this->userService->getAllCoinTransactions();
    }

    public function getById($id)
    {
        return $this->service->getById($id);
    }

    public function getUsersTransactions(Request $request)
    {
        return $this->service->getUserTransactions($request->user()->id);
//        return $this->service->getByParam('user_id', $request->user()->id);
    }

    public function getUserConfirmedTransactions(Request $request)
    {
        $transactions = $this->userService->getUserConfirmedTransactions($request->user()->id);
        $balance = 0;
        for($i = 0; $i < count($transactions); $i++) {
            $balance = $balance + $transactions[$i]->amount;
        }
        session(['current_balance' => $balance]);
        return $transactions;
    }

    public function delete($id)
    {
        return $this->service->delete($id);
    }
}