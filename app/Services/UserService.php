<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/14/2018
 * Time: 12:18 PM
 */

namespace App\Services;

use App\Http\Requests\UserRequest;
use App\Repositories\cointpayment_log_trxRepository;
use App\Repositories\UserRepository;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserService
{

    private $repository;
    private $coinRepository;

    public function __construct(UserRepository $userRepository, cointpayment_log_trxRepository $cointpayment_log_trxRepository)
    {
        $this->repository = $userRepository;
        $this->coinRepository = $cointpayment_log_trxRepository;
    }

    public function authenticate($email, $password, $rememberMe = false)
    {
        return Auth::attempt(['email' => $email, 'password' => $password], $rememberMe);
    }

    public function confirmUserDetails($email, $password)
    {
        $user = $this->repository->getOneByParam('email', $email);
        return $user && Hash::check($password, $user->password) ? $user : null;
    }

    public function create(UserRequest $request, $role)
    {
        if ($user = $this->repository->getOneByParam('email', $request->email)) {
            return back()->withInput()->withErrors(['registerError' => 'sorry the user could not be registered']);
        }
        $user = $this->repository->create($request->getValues());
        if (!$user) {
            return back()->withInput()->withErrors(['registerError' => 'user was not successfully registered']);
        }
        return $this->authenticate($request->email, $request->password)
        ? redirect()->intended('/')
        : redirect('/login');
    }

    public function getAll(int $n = null, array $fields = null)
    {
        return $this->repository->getAll($n, $fields);
    }

    public function getById($id)
    {
        return $this->repository->getById($id);
    }

    public function getAllCoinTransactions()
    {
        return $this->coinRepository->getAll();
    }

    public function getUserConfirmedTransactions($userId)
    {
        return $this->coinRepository->getUserConfirmedTransactions($userId);
    }

    public function getByParam($param, $value)
    {
        return $this->repository->getByParam($param, $value);
    }

    public function enableAutoWithdraw($request)
    {
        if (!$this->repository->update($request->userId, ['auto_withdraw' => $request->autoWithdraw])) {
            return response()->json(['message' => 'the user auto withdraw was not updated'], 500);
        }
        return response()->json(['message' => 'the user auto withdraw was successfully updated'], 200);
    }

    public function update($id, UserRequest $request)
    {
        if (!$this->repository->update($id, $request->getValues())) {
            return response()->json(['message' => 'the resource was not updated', 'data' => $request->getValues()], 500);
        }
        return response()->json(['message' => 'the resource was successfully updated', 'data' => $request->getValues()], 200);
    }

    public function updateWithArray($id, $userArray)
    {
        if (!$this->repository->update($id, $userArray)) {
            return response()->json(['message' => 'the resource was not updated', 'data' => $userArray], 500);
        }
        return response()->json(['message' => 'the resource was successfully updated', 'data' => $userArray], 200);
    }

    public function delete($id)
    {
        if (!$this->repository->delete($id)) {
            return response()->json(['message' => 'the resource was not deleted']);
        }
        return response()->json(['message' => 'the resource was successfully deleted']);
    }

}
