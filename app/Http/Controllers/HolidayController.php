<?php

namespace App\Http\Controllers;

use App\Http\Resources\HolidayCollection;
use App\Http\Resources\HolidayResource;
use App\Services\HolidayService;
use Illuminate\Http\Request;

class HolidayController extends APIBaseController
{
    public function __construct(HolidayService $api)
    {
        parent::__construct($api, HolidayResource::class, HolidayCollection::class);
    }
}
