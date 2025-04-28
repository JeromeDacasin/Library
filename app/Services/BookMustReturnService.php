<?php

namespace App\Services;

use App\Models\BookMustReturn;
use Exception;

class BookMustReturnService {

    public function __construct(private BookMustReturn $model)
    {
        $this->model = $model;
    }

    public function store($request)
    {
        if ($request->days > 15) {
            throw new Exception('Max Days is 15 days', 400); 
        } 

       $model =  $this->model::create($request->all());

        return $model;
    }

    public function index()
    {
        return $this->model::all();
    }

    public function show($id)
    {
        return $this->model::find($id);
    }

    public function update($request, $id)
    {
    
        if ($request->days > 15) {
            throw new Exception('Max Days is 15 days', 400); 
        } 

        $model = $this->model::find($id);

        $model->update($request->all());
        
        return $model;
    }
}