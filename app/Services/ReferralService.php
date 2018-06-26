<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/25/2018
 * Time: 9:33 PM
 */

namespace App\Services;


use App\Repositories\ReferralRepository;
use Illuminate\Http\Request;

class ReferralService {

    private $repository;

    public function __construct(ReferralRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create($data)
    {
        if (!$this->repository->create($data)) {
            return response()->json(['message' => 'the resource was not created', 'data' => $data], 500);
        }
        return response()->json(['message' => 'the resource was successfully created', 'data' => $data], 200);
    }

    public function getUserReferrals($referralCode)
    {
        return $this->repository->getByParam('referee', $referralCode);
    }
}