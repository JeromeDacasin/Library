<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookMustReturnCollection;
use App\Http\Resources\BookMustReturnResource;
use App\Services\BookMustReturnService;
use Illuminate\Http\Request;

class BookMustReturnController extends APIBaseController
{
    public function __construct(BookMustReturnService $api)
    {
        parent::__construct($api, BookMustReturnResource::class, BookMustReturnCollection::class);
    }
}
