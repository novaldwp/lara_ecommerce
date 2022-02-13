<?php

namespace App\Repositories;

use App\Interfaces\NegativeWordRepositoryInterface;
use App\Models\Admin\NegativeWord;

class NegativeWordRepository implements NegativeWordRepositoryInterface {
    protected $model;

    public function __construct(NegativeWord $model)
    {
        $this->model = $model;
    }

    public function getNegativeWords()
    {
        return $this->model->withTrashed()->orderBy('word')->get();
    }

    public function getActiveNegativeWords()
    {
        return $this->model->whereNull('deleted_at')->orderBy('word')->get();
    }

    public function getNonActiveNegativeWords()
    {
        return $this->model->withTrashed()->whereNotNull('deleted_at')->orderBy('word')->get();
    }

    public function getNegativeWordById($word_id)
    {
        return $this->model->withTrashed()->whereId(simple_Decrypt($word_id))->first();
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
