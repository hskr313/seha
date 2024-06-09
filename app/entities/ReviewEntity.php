<?php
class ReviewEntity {
    public int $id;
    public int $service_id;
    public int $reviewer_id;
    public int $rating;
    public string $comment;

    public function __construct(
        int $id = null,
        int $service_id = null,
        int $reviewer_id = null,
        int $rating = 0,
        string $comment = ''
    ) {
        $this->id = $id;
        $this->service_id = $service_id;
        $this->reviewer_id = $reviewer_id;
        $this->rating = $rating;
        $this->comment = $comment;
    }
}
