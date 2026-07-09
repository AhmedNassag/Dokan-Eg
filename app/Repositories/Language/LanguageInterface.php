<?php

namespace App\Repositories\Language;

interface LanguageInterface
{
    public function index($request, $filter);
    
    public function store($request);
    
    public function show($id);
    
    public function update($id, $request);
    
    public function destroy($id);
    
}
