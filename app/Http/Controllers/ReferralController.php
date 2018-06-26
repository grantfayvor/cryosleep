<?php

namespace App\Http\Controllers;

use App\Services\ReferralService;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    private $service;

    public function __construct(ReferralService $referralService)
    {
        $this->service = $referralService;
    }

    public function getUserReferrals($referralCode)
    {
        return $this->service->getUserReferrals($referralCode);
    }
}
