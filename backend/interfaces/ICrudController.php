<?php

namespace Backend\Interfaces;

interface IcrudController {
    public function index();
    public function show($id);
    public function create();
    public function update($id);
    public function delete($id);
}

?>