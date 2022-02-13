<?php

namespace App\Interfaces;

interface PositiveWordRepositoryInterface {
    public function getPositiveWords();
    public function getActivePositiveWords();
    public function getNonActivePositiveWords();
    public function getPositiveWordById($word_id);
    public function store($data);
    public function update($data, $word_id);
    public function delete($word_id);
    public function restore($word_id);
}
