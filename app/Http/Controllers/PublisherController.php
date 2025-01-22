<?php

namespace App\Http\Controllers;

use App\Http\Resources\PublisherCollection;
use App\Http\Resources\PublisherResource;
use Illuminate\Http\Request;
use App\Services\PublisherService;

class PublisherController extends APIBaseController
{
    public function __construct(PublisherService $api)
    {
        parent::__construct($api, PublisherResource::class, PublisherCollection::class);
    }
}
