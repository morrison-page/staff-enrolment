<?php

namespace Backend\Interfaces;

interface ControllerInterface {
    public function index();
    public function show($id);
    public function create();
    public function update($id);
    public function delete($id);
}

?>