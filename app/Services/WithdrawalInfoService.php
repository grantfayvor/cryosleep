<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/18/2018
 * Time: 8:35 AM
 */

namespace App\Services;


use App\Http\Requests\WithdrawalInfoRequest;
use App\Repositories\WithdrawalInfoRepository;

class WithdrawalInfoService {

    private $repository;

    public function __construct(WithdrawalInfoRepository $repository) {
        $this->repository = $repository;
    }

    public function create(WithdrawalInfoRequest $request) {
        $currentBalance = (int) session('current_balance');
        $noOfReferrals = $request->user()->referrals;
        $referralBonus = ($noOfReferrals * 0.05) * $currentBalance;
        if($request->amount > ($currentBalance + $referralBonus)) {
            return response()->json(['message' => 'amount is less than your current balance', 'data' => $request->getValues()], 422);
        }
        if (!$this->repository->create($request->getValues())) {
            return response()->json(['message' => 'the resource was not created', 'data' => $request->getValues()], 500);
        }
        return response()->json(['message' => 'the resource was successfully created', 'data' => $request->getValues()], 200);
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

    public function approveWithdrawalRequest($id) {
        return $this->repository->update($id, [ 'approved' => true ]);
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