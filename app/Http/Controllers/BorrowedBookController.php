<?php

namespace App\Http\Controllers;

use App\Http\Resources\BorrowedBookCollection;
use App\Http\Resources\BorrowedBookResource;
use App\Services\BorrowedBookService;
use Illuminate\Http\Request;

class BorrowedBookController extends APIBaseController
{
    public function __construct(BorrowedBookService $api)
    {
        parent::__construct($api, BorrowedBookResource::class, BorrowedBookCollection::class);
    }

    public function myBooks()
    {
        return $this->api->myBooks();
    }

    public function exports(Request $request)
    {
        return $this->api->index($request);
    }
}
