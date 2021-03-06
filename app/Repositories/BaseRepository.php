<?php
/**
 * Created by PhpStorm.
 * User: ositadinma_nwangwu
 * Date: 6/14/2018
 * Time: 11:35 AM
 */


namespace App\Repositories;


abstract class BaseRepository
{
    protected $model;

    public function getAll($n = null, $url = null, array $fields = null)
    {
        if ($fields && $n) {
            $result = $this->model->orderBy('updated_at', 'desc')->get($fields)->paginate($n);
            if($url != null) $result->withPath($url);
            return $result;
        } elseif (!$fields && $n) {
            $result = $this->model->orderBy('updated_at', 'desc')->paginate($n);
            if($url != null) $result->withPath($url);
            return $result;
        } elseif($fields && !$n) {
            return $this->model->orderBy('updated_at', 'desc')->get($fields);
        } else {
            return $this->model->orderBy('updated_at', 'desc')->get();
        }
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function update($id, array $fields)
    {
        return $this->model->where('id', $id)->update($fields);
    }

    public function delete($id)
    {
        return $this->model->find($id)->delete();
    }

    public function create(array $object)
    {
        return $this->model->create($object);
    }

    public function getByParam($param, $value)
    {
        return $this->model->where($param, $value)->get();
    }

    public function getOneByParam($param, $value)
    {
        return $this->model->where($param, $value)->first();
    }

}

