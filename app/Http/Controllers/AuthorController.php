<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthorCollection;
use App\Http\Resources\AuthorResource;
use App\Services\AuthorService;
use Illuminate\Http\Request;

class AuthorController extends APIBaseController
{
    public function __construct(AuthorService $api)
    {
        parent::__construct($api, AuthorResource::class, AuthorCollection::class);
    }

}
