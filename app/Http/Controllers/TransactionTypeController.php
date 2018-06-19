<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/15/2018
 * Time: 12:21 PM
 */

namespace App\Http\Controllers;


use App\Http\Requests\TransactionTypeRequest;
use App\Services\TransactionTypeService;
use Illuminate\Http\Request;

class TransactionTypeController extends Controller{

    private $service;

    public function __construct(TransactionTypeService $service)
    {
        $this->service = $service;
    }

    public function create(TransactionTypeRequest $request)
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
}