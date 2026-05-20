<?php


interface RepositoryInterface
{
    public function save(array $data);
    public function find($id);
    public function findAll();
    public function delete($id);
}
