<?php

namespace Kirill\Cash\Model;

use Kirill\Cash\Api\Data\HistoryInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;

class History extends AbstractModel implements HistoryInterface, IdentityInterface
{

    const CACHE_TAG = 'cashback_history';
    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;
    /**
     * @var string
     */
    protected $_eventPrefix = self::CACHE_TAG;


    protected function _construct()
    {
        $this->_init('Kirill\Cash\Model\ResourceModel\History');
    }

    /**
     * @return string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return array
     */
    public function getDefaultValues()
    {
        return [];
    }

    /**
     * @return array|int|mixed|null
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * @return array|mixed|null
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * @param $customerId
     * @return History
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * @return array|mixed|string|null
     */
    public function getOperation()
    {
        return $this->getData(self::OPERATION);
    }

    /**
     * @param string $operation
     * @return History
     */
    public function setOperation($operation)
    {
        return $this->setData(self::OPERATION, $operation);
    }

    /**
     * @return array|float|int|mixed|null
     */
    public function getRemainCoin()
    {
        return $this->getData(self::REMAIN_COIN);
    }

    /**
     * @param string $remainCoin
     * @return History
     */
    public function setRemainCoin($remainCoin)
    {
        return $this->setData(self::REMAIN_COIN, $remainCoin);
    }

    /**
     * @return array|float|mixed|null
     */
    public function getOperationSum()
    {
        return $this->getData(self::OPERATION_SUM);
    }

    /**
     * @param $operationSum
     * @return History
     */
    public function setOperationSum($operationSum)
    {
        return $this->setData(self::OPERATION_SUM, $operationSum);
    }

    /**
     * @return array|mixed|string|null
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @return array|mixed|string|null
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @param string $updatedAt
     * @return History
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

}
