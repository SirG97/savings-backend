<?php

namespace App\Providers;

use App\Contracts\BranchRepositoryInterface;
use App\Contracts\CustomerRepositoryInterface;
use App\Contracts\CustomerWalletRepositoryInterface;
use App\Contracts\UserRepositoryInterface;
use App\Contracts\WalletRepositoryInterface;
use App\Repositories\BranchRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\CustomerWalletRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(BranchRepositoryInterface::class, BranchRepository::class);
        $this->app->bind(WalletRepositoryInterface::class, WalletRepository::class);
        $this->app->bind(CustomerWalletRepositoryInterface::class, CustomerWalletRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
