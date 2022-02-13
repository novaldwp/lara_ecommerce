<?php

namespace App\Interfaces;

interface NegativeWordRepositoryInterface {

    public function getNegativeWords();
    public function getActiveNegativeWords();
    public function getNonActiveNegativeWords();
    public function getNegativeWordById($word_id);
    public function store($data);
    public function update($data, $word_id);
    public function delete($word_id);
    public function restore($word_id);
}
