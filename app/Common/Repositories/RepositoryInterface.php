<?php

namespace App\Common\Repositories;

interface RepositoryInterface
{
    public function add($entity);

    public function remove($id);

    public function update($entity);

    public function get($id);
}
