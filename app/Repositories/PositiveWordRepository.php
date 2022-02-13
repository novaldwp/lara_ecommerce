<?php

namespace App\Repositories;

use App\Interfaces\PositiveWordRepositoryInterface;
use App\Models\Admin\PositiveWord;

class PositiveWordRepository implements PositiveWordRepositoryInterface {

    protected $model;

    public function __construct(PositiveWord $model)
    {
        $this->model = $model;
    }

    public function getPositiveWords()
    {
        return $this->model->withTrashed()->orderBy('word')->get();
    }

    public function getActivePositiveWords()
    {
        return $this->model->whereNull('deleted_at')->orderBy('word')->get();
    }

    public function getNonActivePositiveWords()
    {
        return $this->model->withTrashed()->whereNotNull('deleted_at')->orderBy('word')->get();
    }

    public function getPositiveWordById($word_id)
    {
        return $this->model->withTrashed()->whereId(simple_decrypt($word_id))->first();
    }

    public function store($data)
    {
        return $this->model->create($data);
    }

    public function update($data, $word_id)
    {
        return $this->model->withTrashed()->findOrFail(simple_decrypt($word_id))->update($data);
    }

    public function delete($word_id)
    {
        return $this->model->findOrFail(simple_decrypt($word_id))->delete();
    }

    public function restore($word_id)
    {
        return $this->model->withTrashed()->findOrFail(simple_decrypt($word_id))->restore();
    }
}
