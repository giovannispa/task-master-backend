<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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
        return Cache::rememberForever(class_basename($this->model::class), function() {
            return $this->model->get()->toArray();
        });
    }

    /**
     * Função que resgata dado especifico por ID.
     *
     * @param int $id
     * @param bool $loadRelationships
     * @return object
     */
    public function find(int $id, bool $loadRelationships = false): object
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
        $this->clearCache();
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
        $this->clearCache();
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
            $this->clearCache();
            $model->delete();
            return true;
        }

        return false;
    }

    /**
     * Faz relacionamento many to many de forma dinamica.
     *
     * @param string $relation
     * @param int $primary_id
     * @param int $foreign_id
     * @return object|null
     */
    public function attach(string $relation, int $primary_id, int $foreign_id): ?object
    {
        $model = $this->find($primary_id);
        if ($model) {
            $this->clearCache();
            $model->{$relation}()->attach($foreign_id);
            return $model;
        }

        return null;
    }

    /**
     * Remove do relacionamento many to many de forma dinamica.
     *
     * @param string $relation
     * @param int $primary_id
     * @param int $foreign_id
     * @return object|null
     */
    public function detach(string $relation, int $primary_id, int $foreign_id): ?object
    {
        $model = $this->find($primary_id);
        if ($model) {
            $this->clearCache();
            $model->{$relation}()->detach($foreign_id);
            return $model;
        }

        return null;
    }

    /**
     * Apaga o cache para que ele seja criado novamente.
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::forget($this->model::class);
    }
}
