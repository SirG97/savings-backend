<?php

namespace App\Services;

use Illuminate\Http\Request;

class DashboardService
{

    /**
     * Handle the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return null|array
     */
    public function handle(Request $request): null|array
    {
        $validated = $request->validated();

        return null;
    }
}
