<?php
class TransactionEntity {
    public int $id;
    public int $service_id;
    public int $provider_id;
    public int $receiver_id;
    public int $hours_exchanged;
    public string $transaction_date;

    public function __construct(
        int $id = null,
        int $service_id = null,
        int $provider_id = null,
        int $receiver_id = null,
        int $hours_exchanged = 0,
        string $transaction_date = ''
    ) {
        $this->id = $id;
        $this->service_id = $service_id;
        $this->provider_id = $provider_id;
        $this->receiver_id = $receiver_id;
        $this->hours_exchanged = $hours_exchanged;
        $this->transaction_date = $transaction_date;
    }
}
