<?php

namespace App\Http\Controllers;

use App\Http\Resources\PenaltyCollection;
use App\Http\Resources\PenaltyResource;
use App\Services\PenaltyService;
use Illuminate\Http\Request;

class PenaltyController extends APIBaseController
{
    public function __construct(PenaltyService $api)
    {
        parent::__construct($api, PenaltyResource::class, PenaltyCollection::class);
    }
}
