<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/14/2018
 * Time: 12:51 PM
 */

namespace App\Services;


use App\Http\Requests\TransactionPlanRequest;
use App\Repositories\TransactionPlanRepository;

class TransactionPlanService {

    private $repository;

    public function __construct(TransactionPlanRepository $repository) {
        $this->repository = $repository;
    }

    public function create(TransactionPlanRequest $request) {
        if (!$this->repository->create($request->getValues())) {
            return response()->json(['message' => 'the resource was not created', 'data' => $request->getValues()], 500);
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

    public function delete($id)
    {
        if (!$this->repository->delete($id)) {
            return response()->json(['message' => 'the resource was not deleted']);
        }
        return response()->json(['message' => 'the resource was successfully deleted']);
    }
}