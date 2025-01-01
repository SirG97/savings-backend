<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface DashboardRepositoryInterface
{

    /**
     * Do something.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function getTotalUsers(): int;

    /**
     * Do something.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function getTotalBalance(): int;

    /*
     *
     * @param array $filters
     */
    public function getTransactionSummaryByType(array $filters = []): array;
}
