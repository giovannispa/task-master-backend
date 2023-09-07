<?php

namespace App\Services;

use App\Interfaces\CategoryRepositoryInterface;

/**
 * Classe CategoryService
 *
 * Esta classe é um serviço responsável por manipular os dados de categorias.
 * Ela depende de uma implementação do repositório de categoria
 * para realizar as operações de CRUD.
 */
class CategoryService
{
    /**
     * @var CategoryRepositoryInterface
     */
    private CategoryRepositoryInterface $repository;

    /**
     * Construtor.
     *
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->repository = $categoryRepository;
    }

    /**
     * Encontra a primeira categoria que corresponda a um valor específico de uma coluna.
     *
     * @param string $column
     * @param mixed $value
     * @return object
     */
    public function findWhereFirst(string $column, mixed $value): object
    {
        return $this->repository->findWhereFirst($column, $value);
    }

    /**
     * Retorna todos as categorias.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->repository->all();
    }

    /**
     * Cria uma nova categoria.
     *
     * @param array $data
     * @return object
     */
    public function create(array $data): object
    {
        return $this->repository->create($data);
    }

    /**
     * Encontra uma categoria pelo ID.
     *
     * @param int $id
     * @return object
     */
    public function find(int $id): object
    {
        return $this->repository->find($id);
    }

    /**
     * Deleta uma categoria pelo ID.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Atualiza uma categoria pelo ID.
     *
     * @param int $id
     * @param array $data
     * @return object
     */
    public function update(int $id, array $data): object
    {
        return $this->repository->update($id, $data);
    }
}
