<?php

namespace App\Providers;

use App\Interfaces\BaseRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\ProjectRepositoryInterface;
use App\Interfaces\TaskRepositoryInterface;
use App\Interfaces\TeamRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;
use App\Repositories\TeamRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(TeamRepositoryInterface::class, TeamRepository::class);
        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
