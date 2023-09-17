<?php

namespace App\Services;

use App\Interfaces\ProjectRepositoryInterface;

/**
 * Classe ProjectService
 *
 * Esta classe é um serviço responsável por manipular os dados de projetos.
 * Ela depende de uma implementação do repositório de projeto
 * para realizar as operações de CRUD.
 */
class ProjectService
{
    /**
     * @var ProjectRepositoryInterface
     */
    private ProjectRepositoryInterface $repository;

    /**
     * Construtor.
     *
     * @param ProjectRepositoryInterface $projectRepository
     */
    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->repository = $projectRepository;
    }

    /**
     * Encontra primeiro projeto que corresponda a um valor específico de uma coluna.
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
     * Retorna todos os projetos.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->repository->all();
    }

    /**
     * Cria um novo projeto.
     *
     * @param array $data
     * @return object
     */
    public function create(array $data): object
    {
        return $this->repository->create($data);
    }

    /**
     * Encontra um projeto pelo ID.
     *
     * @param int $id
     * @param bool $loadRelationships
     * @return object
     */
    public function find(int $id, bool $loadRelationships = false): object
    {
        return $this->repository->find($id, $loadRelationships);
    }

    /**
     * Deleta uma projeto pelo ID.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Atualiza um projeto pelo ID.
     *
     * @param int $id
     * @param array $data
     * @return object
     */
    public function update(int $id, array $data): object
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Faz o relacionamento entre projeto e equipe.
     *
     * @param string $relation
     * @param int $primary_id
     * @param int $foreign_id
     * @return object|null
     */
    public function attach(string $relation, int $primary_id, int $foreign_id): ?object
    {
        return $this->repository->attach($relation, $primary_id, $foreign_id);
    }

    /**
     * Remove o relacionamento entre projeto e equipe.
     *
     * @param string $relation
     * @param int $primary_id
     * @param int $foreign_id
     * @return object|null
     */
    public function detach(string $relation, int $primary_id, int $foreign_id): ?object
    {
        return $this->repository->detach($relation, $primary_id, $foreign_id);
    }
}
