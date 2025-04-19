<?php

namespace App\Http\Controllers;

use App\Models\BorrowedLimit;
use App\Utils\Helper;
use Illuminate\Http\Request;

class HelperController extends Controller
{
    public function roleChecker(Request $request)
    {  
        return Helper::roleWithDisable($request->model);
    }
}
