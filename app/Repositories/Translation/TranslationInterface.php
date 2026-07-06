<?php

namespace App\Repositories\Translation;

interface TranslationInterface
{
    public function getModel();
    public function index($request, $filter);
    public function store($request);
    public function show($id);
    public function update($id, $request);
    public function destroy($id);
    public function export($code);
}
