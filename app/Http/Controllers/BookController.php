<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Services\BookService;

class BookController extends APIBaseController
{
    public function __construct(BookService $api)
    {
        parent::__construct($api, BookResource::class, BookCollection::class);
    }

}
