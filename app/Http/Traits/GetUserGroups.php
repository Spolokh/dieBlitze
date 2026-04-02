<?php

namespace App\Http\Traits;

use App\Models\Groups;
use Illuminate\Support\Collection;

trait GetUserGroups
{
    public function __construct()
    {
    }

    protected function getGroups(?int $id = null): string|array|null
    {
        $groups = cache()->remember('usergroups', 3600, fn() => 
            Groups::pluck('name', 'id')
        );
        return isset($id) ? $groups[$id] : $groups->toArray();
    }
}
