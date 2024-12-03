<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


interface APIInterface 
{
    public function index(Request $request);

    public function show(string $id);

    public function update(Request $request, string $id);

    public function store(Request $request);

    public function destroy(string $id);

}