<?php

namespace App\Repositories;

interface BaseRepository {
  public function store($attributes);
  public function update($model, $attributes);
  public function delete($model);
}
