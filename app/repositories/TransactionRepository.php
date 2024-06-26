<?php
class TransactionRepository extends BaseRepository {
    public function __construct() {
        parent::__construct('transactions', TransactionEntity::class);
    }
}
