<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/18/2018
 * Time: 8:39 AM
 */

namespace App\Http\Controllers;


use App\Http\Requests\WithdrawalInfoRequest;
use App\Services\WithdrawalInfoService;
use Illuminate\Http\Request;

class WithdrawalInfoController extends Controller {

    private $service;

    public function __construct(WithdrawalInfoService $service)
    {
        $this->service = $service;
    }

    public function create(WithdrawalInfoRequest $request)
    {
        return $this->service->create($request);
    }

    public function getAll(Request $request)
    {
        $n = $request->input('n') ?: null;
        $fields = $request->input('fields') ? explode(',', $request->input('fields')) : null;
        return $this->service->getAll($n, $fields);
    }

    public function getById($id)
    {
        return $this->service->getById($id);
    }

    public function delete($id)
    {
        return $this->service->delete($id);
    }

    public function approveWithdrawalRequest($id)
    {
        return $this->service->approveWithdrawalRequest($id);
    }

    public function getUnapprovedWithdrawals()
    {
        return $this->service->getByParam('approved', false);
    }

}