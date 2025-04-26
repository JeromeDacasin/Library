<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Services\BookService;
use Illuminate\Http\Request;

class BookController extends APIBaseController
{
    public function __construct(BookService $api)
    {
        parent::__construct($api, BookResource::class, BookCollection::class);
    }

    public function restore($id)
    {
        return $this->api->restore($id);
    }

    public function archive(Request $request, $id)
    {
        $this->api->archive($request, $id);

        return response()->json([
            'message' => 'Archived Successfully',
            'status' => 200
        ], 200);
    }
}
