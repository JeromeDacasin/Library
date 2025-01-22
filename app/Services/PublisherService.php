<?php

namespace App\Services;

use App\Models\Publisher;


class PublisherService 
{
    public function __construct(protected Publisher $publisher) 
    {

    }

    public function index($request)
    {
        if ($request->search) {
            return $this->publisher::where('name', 'LIKE', '%' .$request->search .'%')->paginate(10);
        }

        return $request->paginate ? $this->publisher::paginate(10) : $this->publisher->get();   
    }

    public function store($request)
    {
        return $this->publisher::create($request->all());
    }

    public function show($id)
    {
        return $this->publisher::findOrFail($id);
    }

    public function destroy($id) 
    {
        $publisher = $this->show($id);

        return $publisher->delete();
    }

    public function update($request, $id)
    {
        $publisher = $this->show($id);
        
        $publisher->update($request->all());
        
        return $publisher;
    }
}