<?php

class ServiceEntity {
    public ?int $id;
    public int $user_id;
    public ?int $category_id;
    public ?string $name;
    public ?string $description;
    public bool $is_published;

    public function __construct(
        ?int $id = null,
        int $user_id,
        ?int $category_id = null,
        ?string $name = '',
        ?string $description = '',
        bool $is_published = false
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->category_id = $category_id;
        $this->name = $name;
        $this->description = $description;
        $this->is_published = $is_published;
    }
}
