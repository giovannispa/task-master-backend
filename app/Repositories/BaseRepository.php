<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * Construtor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Função que resgata todos os dados do banco
     *
     * @return array
     */
    public function all(): array
    {
        return $this->model->all()->toArray();
    }

    /**
     * Função que resgata dado especifico por ID.
     *
     * @param int $id
     * @return object
     */
    public function find(int $id): object
    {
        return $this->model->find($id);
    }

    /**
     * Função que retorna o primeiro registro com base na coluna e valor passado por parametro.
     *
     * @param string $column
     * @param mixed $value
     * @return mixed
     */
    public function findWhereFirst(string $column, mixed $value): object
    {
        return $this->model->where($column, $value)->first();
    }

    /**
     * Função que retorna a coleção com base na coluna e valor passado por parametro.
     *
     * @param string $column
     * @param mixed $value
     * @return object
     */
    public function findWhereAll(string $column, mixed $value): object
    {
        return $this->model->where($column, $value)->get();
    }

    /**
     * Função que cria os dados com base no array informado
     *
     * @param array $data
     * @return object
     */
    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    /**
     * Função que atualiza os dados por ID.
     *
     * @param int $id
     * @param array $data
     * @return object
     */
    public function update(int $id, array $data): object
    {
        $model = $this->find($id);
        $model->fill($data);
        $model->save();
        return $model;
    }

    /**
     * Função que deleta os dados do ID informado.
     *
     * @param $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $model = $this->find($id);

        if ($model) {
            $model->delete();
            return true;
        }

        return false;
    }
}
