<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/18/2018
 * Time: 8:35 AM
 */

namespace App\Services;

use App\Http\Requests\TransactionRequest;
use App\Http\Requests\WithdrawalInfoRequest;
use App\Repositories\WithdrawalInfoRepository;

class WithdrawalInfoService
{

    private $repository;

    public function __construct(WithdrawalInfoRepository $repository, TransactionService $txnService)
    {
        $this->repository = $repository;
        $this->txnService = $txnService;
    }

    public function create(WithdrawalInfoRequest $request)
    {
        $currentBalance = (int) session('current_balance');
        $noOfReferrals = $request->user()->referrals;
        $referralBonus = ($noOfReferrals * 0.05) * $currentBalance;
        if ($request->amount > ($currentBalance + $referralBonus)) {
            return response()->json(['message' => 'amount is less than your current balance', 'data' => $request->getValues()], 422);
        }
        $withdrawalInfo = $this->repository->create($request->getValues());
        if (!$withdrawalInfo) {
            return response()->json(['message' => 'the resource was not created', 'data' => $request->getValues()], 500);
        }
        $result = "";
        if ($request->user()->auto_withdraw == true || $request->user()->auto_withdraw == 1) {
            $txnRequest = [];
            $txnRequest['amount_usd'] = $withdrawalInfo->amount;
            $txnRequest['address'] = $withdrawalInfo->address;
            $txnRequest['currency'] = $withdrawalInfo->currency;
            $txnRequest['withdrawal_info_id'] = $withdrawalInfo->id;
            $txnRequest['user_id'] = $request->user()->id;
            $url = $this->txnService->generateAutoWithdrawalURL($txnRequest);
            $result = ['url' => $url];
        } else {
            $result = $request->getValues();
        }
        return response()->json(['message' => 'the resource was successfully created', 'data' => $result], 200);
    }

    public function getAll($n = null, array $fields = null)
    {
        return $this->repository->getAll($n, $fields);
    }

    public function getById($id)
    {
        return $this->repository->getById($id);
    }

    public function delete($id)
    {
        if (!$this->repository->delete($id)) {
            return response()->json(['message' => 'the resource was not deleted']);
        }
        return response()->json(['message' => 'the resource was successfully deleted']);
    }

    public function approveWithdrawalRequest($id)
    {
        return $this->repository->update($id, ['approved' => true]);
    }

    public function getByParam($param, $value)
    {
        return $this->repository->getByParam($param, $value);
    }

    public function getOneByParam($param, $value)
    {
        return $this->repository->getOneByParam($param, $value);
    }
}
