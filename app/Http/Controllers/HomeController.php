<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\cointpayment_log_trxRepository;
use App\Repositories\UserRepository;
use App\Repositories\WithdrawalInfoRepository;
use App\Services\TransactionTypeService;
use App\Services\TransactionPlanService;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('index', [ 'user' => $request->user()]);
    }

    public function home(Request $request, cointpayment_log_trxRepository $transactionRepo, UserRepository $userRepo,
             WithdrawalInfoRepository $withdrawalRepo, TransactionTypeService $typeService, TransactionPlanService $planService)
    {
        $transactions = $transactionRepo->getAll();
        $transactionsResult = [];
        // $noOfDeposits = $transactions->count();
        $noOfDeposits = 0;
        $noOfUsers = $userRepo->getAll()->count();
        // $noOfWithdrawals = $withdrawalRepo->getAll()->count();
        $noOfWithdrawals = 0;
        foreach($transactions as $transaction) {
            $transaction->payload = json_decode($transaction->payload);
            $typeId = $transaction->payload->transaction_type_id ?: $transaction->payload->details->transaction_type_id;
            $planId = $transaction->payload->transaction_plan_id ?: $transaction->payload->details->transaction_plan_id;
            $type = $typeService->getById($typeId);
            $plan = $planService->getById($planId);
            $transaction->transaction_type = $type;
            $transaction->transaction_plan = $plan;
            if(preg_match('/deposit/i', $type->name)) {
                $noOfDeposits += 1;
            } else {
                $noOfWithdrawals += 1;
            }
            array_push($transactionsResult, $transaction);
        }
        $transactionsResult = array_chunk($transactionsResult, 10)[0];
        // dd($transactionsResult);
        return view('pages.home', ['deposits' => $noOfDeposits, 'users' => $noOfUsers, 'withdrawals' => $noOfWithdrawals, 'transactions' => $transactionsResult]);
    }

    public function about (Request $request)
    {
        return view('pages.about');
    }

    public function getStarted(Request $request)
    {
        return view('pages.get_started');
    }

    public function terms(Request $request)
    {
        return view('pages.terms');
    }

    public function contactUs(Request $request)
    {
        return view('pages.contact');
    }
}
