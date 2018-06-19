<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/14/2018
 * Time: 12:53 PM
 */

namespace App\Services;


use App\Http\Requests\TransactionTypeRequest;
use App\Repositories\TransactionTypeRepository;

class TransactionTypeService {

    private $repository;

    public function __construct(TransactionTypeRepository $repository) {
        $this->repository = $repository;
    }

    public function create(TransactionTypeRequest $request) {
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

    public function getOneByParam($param, $value)
    {
        return $this->repository->getOneByParam($param, $value);
    }
}