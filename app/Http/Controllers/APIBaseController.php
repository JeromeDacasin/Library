<?php 

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class APIBaseController extends Controller implements APIInterface
{
    protected $api;
    protected $resource;
    protected $collection;

    public function __construct($api, $resource, $collection)
    {
        $this->api        = $api;      
        $this->resource   = $resource;  
        $this->collection = $collection;  
    }


    public function index()
    {
        $data = $this->api->index();
       
        return  new $this->collection($data);
    }

    public function store(Request $request)
    {
        $data = $this->api->store($request);

        return response()->json([
            'data'    => new $this->resource($data),
            'status'  => 200,
            'message' => 'ok'
        ], 200);

    }

    public function show($id)
    {
        try {
            $data = $this->api->show($id);
            return response()->json([
                'data'    => new $this->resource($data),
                'status'  => 200,
                'message' => 'ok'
            ], 200);
        } catch(ModelNotFoundException $e) {
            return response()
            ->json([
                'status_code' => 404,
                'message'     => 'Unable to find requested data'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $this->api->update($request, $id);
            return response()->json([
                'data'    => new $this->resource($data),
                'status'  => 200,
                'message' => 'ok'
            ], 200);
        } catch(ModelNotFoundException $e) {
            return response()
            ->json([
                'status_code' => 404,
                'message'     => 'Unable to find requested data'
            ], 404);
        } catch (Exception $e) {
            return response()
            ->json([
                'status_code' => $e->getCode(),
                'message'     => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $this->api->destroy($id);

            return response()->json([
                'status'  => 200,
                'message' => 'Successfully Deleted'
            ], 200);
        } catch(ModelNotFoundException $e) {
            return response()
            ->json([
                'status_code' => 404,
                'message'     => 'Unable to find requested data'
            ], 404);
        }
    }
}