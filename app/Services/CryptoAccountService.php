<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/14/2018
 * Time: 12:27 PM
 */

namespace App\Services;


use App\Http\Requests\CryptoAccountRequest;
use App\Repositories\CryptoAccountRepository;

class CryptoAccountService
{

    private $repository;

    public function __construct(CryptoAccountRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(CryptoAccountRequest $request)
    {
        // $checkExistingAddress = $this->repository->getOneByParam('address', $request->address);
        $checkExistingUser = $this->getOneByParam('user_id', $request->user()->id);
        // if ($checkExistingAddress || $checkExistingUser) {
        if ($checkExistingUser) {
            $result = $this->repository->update($checkExistingUser->id, $request->getValues());
            // return response()->json(['message' => 'you have previously registered your crypto account']);
            if (!$result) {
                return response()->json(['message' => 'the resource was not updated', 'data' => $request->getValues()], 500);
            }
        } else {
            $result = $this->repository->create($request->getValues());
            if (!$result) {
                return response()->json(['message' => 'the resource was not created', 'data' => $request->getValues()], 500);
           }
        }
        return response()->json(['message' => 'the resource was successfully created', 'data' => $request->getValues()], 200);
    }

    public function getAll(int $n = null, array $fields = null)
    {
        return $this->repository->getAll($n, $fields);
    }

    public function getById($id)
    {
        return $this->repository->getById($id);
    }

    public function getOneByParam($param, $value)
    {
        return $this->repository->getOneByParam($param, $value);
    }

    public function delete($id)
    {
        if (!$this->repository->delete($id)) {
            return response()->json(['message' => 'the resource was not deleted']);
        }
        return response()->json(['message' => 'the resource was successfully deleted']);
    }
}