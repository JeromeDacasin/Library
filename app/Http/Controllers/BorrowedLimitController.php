<?php

namespace App\Http\Controllers;

use App\Http\Resources\BorrowedLimitCollection;
use App\Http\Resources\BorrowedLimitResource;
use App\Services\BorrowedLimitService;


class BorrowedLimitController extends APIBaseController
{
    public function __construct(BorrowedLimitService $api)
    {
        parent::__construct($api, BorrowedLimitResource::class, BorrowedLimitCollection::class);
    }
    
}
