<?php

class CategoryRepository extends BaseRepository
{
    public function __construct() {
        parent::__construct('categories', CategoryEntity::class);
    }

}