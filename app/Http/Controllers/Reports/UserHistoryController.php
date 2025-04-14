<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Services\UserHistoryService;
use Illuminate\Http\Request;

class UserHistoryController extends Controller
{
    public function __construct(protected UserHistoryService $api)
    {
        
    }

    public function userHistory($id)
    {
        return $this->api->getHistoryUser($id);
    }
}
