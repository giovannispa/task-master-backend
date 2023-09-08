<?php

namespace App\Services;

use App\Interfaces\TeamRepositoryInterface;

/**
 * Classe TeamService
 *
 * Esta classe é um serviço responsável por manipular os dados de equipes.
 * Ela depende de uma implementação do repositório de equipe
 * para realizar as operações de CRUD.
 */
class TeamService
{
    /**
     * @var TeamRepositoryInterface
     */
    private TeamRepositoryInterface $repository;

    /**
     * Construtor.
     *
     * @param TeamRepositoryInterface $teamRepository
     */
    public function __construct(TeamRepositoryInterface $teamRepository)
    {
        $this->repository = $teamRepository;
    }

    /**
     * Encontra a primeira equipe que corresponda a um valor específico de uma coluna.
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
     * Cria uma nova equipe.
     *
     * @param array $data
     * @return object
     */
    public function create(array $data): object
    {
        return $this->repository->create($data);
    }

    /**
     * Encontra uma equipe pelo ID.
     *
     * @param int $id
     * @return object
     */
    public function find(int $id): object
    {
        return $this->repository->find($id);
    }

    /**
     * Deleta uma equipe pelo ID.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Atualiza uma equipe pelo ID.
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
     * Faz o relacionamento entre equipe e membro da equipe.
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
     * Remove o relacionamento entre equipe e membro da equipe.
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
