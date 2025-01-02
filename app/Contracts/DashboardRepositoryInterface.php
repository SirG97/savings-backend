<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface DashboardRepositoryInterface
{

    /**
     * Do something.
     *
     * @param int $id
     * @return int
     */
    public function getTotalUsers(int $id = null): int;

    /**
     * Do something.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function getTotalBalance(int $id = null): int;

    /*
     *
     * @param array $filters
     */
    public function getTransactionSummaryByType(array $filters = [], int $id = null): array;
}
