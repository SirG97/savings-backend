<?php

namespace App\Providers;

use App\Contracts\BranchRepositoryInterface;
use App\Contracts\CustomerRepositoryInterface;
use App\Contracts\CustomerTransactionRepositoryInterface;
use App\Contracts\CustomerWalletRepositoryInterface;
use App\Contracts\DashboardRepositoryInterface;
use App\Contracts\LoanApplicationRepositoryInterface;
use App\Contracts\LoanRepositoryInterface;
use App\Contracts\TransactionRepositoryInterface;
use App\Contracts\UserRepositoryInterface;
use App\Contracts\WalletRepositoryInterface;
use App\Repositories\BranchRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\CustomerTransactionRepository;
use App\Repositories\CustomerWalletRepository;
use App\Repositories\DashboardRepository;
use App\Repositories\LoanApplicationRepository;
use App\Repositories\LoanRepository;
use App\Repositories\TransactionRepository;
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
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(CustomerTransactionRepositoryInterface::class, CustomerTransactionRepository::class);
        $this->app->bind(DashboardRepositoryInterface::class, DashboardRepository::class);
        $this->app->bind(LoanRepositoryInterface::class, LoanRepository::class);
        $this->app->bind(LoanApplicationRepositoryInterface::class, LoanApplicationRepository::class);
        
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
