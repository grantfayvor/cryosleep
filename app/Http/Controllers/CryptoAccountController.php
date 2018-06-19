<?php
/**
 * Created by PhpStorm.
 * User: Harrison
 * Date: 6/15/2018
 * Time: 12:19 PM
 */

namespace App\Http\Controllers;


use App\Http\Requests\CryptoAccountRequest;
use App\Services\CryptoAccountService;
use Illuminate\Http\Request;

class CryptoAccountController extends Controller
{

    private $service;

    public function __construct(CryptoAccountService $service)
    {
        $this->service = $service;
    }

    public function create(CryptoAccountRequest $request)
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

    public function confirmAddress(Request $request)
    {
        return response()->json($this->service->getOneByParam('user_id', $request->user()->id) != null);
    }

    public function getUserAddress(Request $request)
    {
        return response()->json($this->service->getOneByParam('user_id', $request->user()->id));
    }

    public function delete($id)
    {
        return $this->service->delete($id);
    }
}