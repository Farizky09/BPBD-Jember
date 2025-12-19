<?php

namespace App\Http\Controllers\Interfaces;

use Illuminate\Support\Collection;
use Dotenv\Util\Str;

interface DashboardInterfaces
{
    public function getSubdistrictCount(?String $startDate = null, ?String $endDate = null): Collection;
    public function getDisasterCategoryCount(?String $startDate = null, ?String $endDate = null): Collection;
    public function getDataStats(?String $startDate = null, ?String $endDate = null): array;
}
