<?php
class CategoryEntity {
    public int $id;
    public string $category_logical_value;
    public string $category_name;

    public function __construct(
        int $id = null,
        string $category_logical_value = '',
        string $category_name = ''
    ) {
        $this->id = $id;
        $this->category_logical_value = $category_logical_value;
        $this->category_name = $category_name;
    }
}
