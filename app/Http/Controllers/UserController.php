<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/18/2018
 * Time: 9:17 AM
 */

namespace App\Http\Controllers;


use App\Services\TransactionService;
use App\Services\UserService;
use App\Services\WithdrawalInfoService;
use Illuminate\Http\Request;

class UserController {

    private $service;
    private $transactionService;
    private $withdrawalService;

    public function __construct(UserService $userService, TransactionService $transactionService, WithdrawalInfoService $infoService)
    {
        $this->service = $userService;
        $this->transactionService = $transactionService;
        $this->withdrawalService = $infoService;
    }

    public function getTransactionsInformation(Request $request)
    {
        $transactions = $this->transactionService->getUserTransactions($request->user()->id);
        $withdrawals = $this->withdrawalService->getByParam('user_id', $request->user()->id);
        return response()->json(['transactions' => $transactions, 'withdrawals' => $withdrawals]);
    }

    public function getAll()
    {
        return $this->service->getAll();
    }

    public function delete($id)
    {
        return $this->service->delete($id);
    }

    public function getUserRoles($userId) {
        $user = $this->service->getById($userId);
        return response()->json($user->isAn('ADMIN') ? 'ADMIN' : 'USER');
    }
}