<?php
class ServiceEntity {
    public int $id;
    public int $user_id;
    public int $category_id;
    public string $service_type;
    public string $title;
    public string $description;
    public bool $is_published;

    public function __construct(
        int $id = null,
        int $user_id = null,
        int $category_id = null,
        string $service_type = '',
        string $title = '',
        string $description = '',
        bool $is_published = false
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->category_id = $category_id;
        $this->service_type = $service_type;
        $this->title = $title;
        $this->description = $description;
        $this->is_published = $is_published;
    }
}
