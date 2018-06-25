<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/18/2018
 * Time: 9:17 AM
 */

namespace App\Http\Controllers;


use App\Services\RolesAndClaimsService;
use App\Services\TransactionService;
use App\Services\UserService;
use App\Services\WithdrawalInfoService;
use Illuminate\Http\Request;

class UserController {

    private $service;
    private $transactionService;
    private $withdrawalService;
    private $rolesService;

    public function __construct(UserService $userService, TransactionService $transactionService,
                                WithdrawalInfoService $infoService, RolesAndClaimsService $rolesService)
    {
        $this->service = $userService;
        $this->transactionService = $transactionService;
        $this->withdrawalService = $infoService;
        $this->rolesService = $rolesService;
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

    public function update(Request $request)
    {
        if($request->role && $request->previousRole) {
            $user = $this->service->getById($request->userId);
            $this->rolesService->assignRole($user, $request->role);
            $this->rolesService->retractUserRole($user, $request->previousRole);
        }
        return $this->service->updateWithArray($request->userId, ['full_name' => $request->fullName, 'email' => $request->email]);
    }
}