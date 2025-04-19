<?php

namespace App\Http\Controllers;

use App\Imports\BookImport;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function __construct(protected BookImport $bookImport)
    {
        
    }

    public function importBook(Request $request)
    {
        try {
            Excel::import($this->bookImport, $request->file('file'));
        } catch (Exception $e) {
            return response()->json([
                'status'  => 400,
                'message' => 'something went wrong',
                'data'    => $e
            ], 400);
        }
        
        return response()->json([
            'status'  => 200,
            'message' => 'Successfully Imported'
        ], 200);
        
    }
}
