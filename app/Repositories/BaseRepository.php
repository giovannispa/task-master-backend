<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Função que resgata todos os dados do banco
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Função que resgata dado especifico por ID.
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * Função que cria os dados com base no array informado
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Função que atualiza os dados por ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed|null
     */
    public function update(int $id, array $data)
    {
        $model = $this->find($id);

        if ($model) {
            $model->fill($data);
            $model->save();
            return $model;
        }

        return null;
    }

    /**
     * Função que deleta os dados do ID informado.
     *
     * @param $id
     * @return bool
     */
    public function delete(int $id)
    {
        $model = $this->find($id);

        if ($model) {
            $model->delete();
            return true;
        }

        return false;
    }

    public function findFirst(string $column, mixed $value)
    {
        return $this->model->where($column, $value)->first();
    }
}
