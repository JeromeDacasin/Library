<?php

namespace App\Http\Controllers;

use App\Http\Resources\DepartmentCollection;
use App\Http\Resources\DepartmentResource;
use App\Services\DepartmentService;

class DepartmentController extends APIBaseController
{
    public function __construct(DepartmentService $api)
    {
        parent::__construct($api, DepartmentResource::class, DepartmentCollection::class);
    }
}
