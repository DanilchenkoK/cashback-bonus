<?php


namespace Kirill\Cash\Api\Data;

interface HistoryInterface
{

    /**
     * Constants for keys of data array.
     */
    const ID = 'id';
    const CUSTOMER_ID = 'customer_id';
    const OPERATION = 'operation';
    const REMAIN_COIN = 'remain_coin';
    const SUM = 'sum';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    /**
     * Get id
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get Customer id
     *
     * @return mixed
     */
    public function getCustomerId();


    /**
     * Set Customer id
     *
     * @return $this
     */
    public function setCustomerId($customerId);

    /**
     * Get operation
     *
     * @return string|null
     */
    public function getOperation();

    /**
     * Set operation
     *
     * @param string $operation
     * @return $this
     */
    public function setOperation($operation);

    /**
     * Get remain coin
     *
     * @return float|int|null
     */
    public function getRemainCoin();

    /**
     * Set remain coin
     *
     * @param string $remainCoin
     * @return $this
     */
    public function setRemainCoin($remainCoin);


    /**
     * Get operation sum
     *
     * @return float|null
     */
    public function getSum();

    /**
     * Set operation sum
     *
     * @param string $operationSum
     * @return $this
     */
    public function setSum($operationSum);


    /**
     * Get created date
     *
     * @return string|null
     */
    public function getCreatedAt();



    /**
     * Get updated date
     *
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated date
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt);


}
