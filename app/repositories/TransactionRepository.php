<?php
class TransactionRepository extends BaseRepository {
    public function __construct() {
        parent::__construct('transactions', TransactionEntity::class);
    }

    public function createTransaction(ServiceRequestEntity $serviceRequest) {
        $transactionEntity = new TransactionEntity(
            null,
            $serviceRequest->service_id,
            $serviceRequest->provider_id,
            $serviceRequest->requester_id,
            $serviceRequest->requested_hours,
            date('Y-m-d H:i:s')
        );

        return $this->create($transactionEntity);
    }
}
