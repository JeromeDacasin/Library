<?php

namespace App\Http\Controllers;

use App\Http\Resources\BorrowedBookCollection;
use App\Http\Resources\BorrowedBookResource;
use App\Services\BorrowedBookService;

class BorrowedBookController extends APIBaseController
{
    public function __construct(BorrowedBookService $api)
    {
        parent::__construct($api, BorrowedBookResource::class, BorrowedBookCollection::class);
    }

    // public function store(Request $request)
    // {      
    //     $this->api->store($request);
    // }

    // public function update(Request $request, $id)
    // {
    //     try {

    //         $users = $this->api->update($request, $id);

    //         return response()->json([
    //             'data'    => $users,
    //             'status'  => 200,
    //             'message' => 'Successful Update'
    //         ]);

    //     } catch (Exception $e) {
    //         return $e;
    //         return response()->json([
    //             'message' => $e->getMessage(),
    //             'status'  => $e->getCode()
    //         ],$e->getCode());
    //     } 
    // }

    // public function show($id)
    // {
    //     return $this->api->show($id);
    // }

    // public function index()
    // {
    //     return $this->api->index();
    // }
}
