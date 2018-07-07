<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\cointpayment_log_trxRepository;
use App\Repositories\UserRepository;
use App\Repositories\WithdrawalInfoRepository;

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

    public function home(Request $request, cointpayment_log_trxRepository $transactionRepo, 
                            UserRepository $userRepo, WithdrawalInfoRepository $withdrawalRepo)
    {
        $noOfDeposits = $transactionRepo->getAll()->count();
        $noOfUsers = $userRepo->getAll()->count();
        $noOfWithdrawals = $withdrawalRepo->getAll()->count();
        return view('pages.home', ['deposits' => $noOfDeposits, 'users' => $noOfUsers, 'withdrawals' => $noOfWithdrawals]);
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
