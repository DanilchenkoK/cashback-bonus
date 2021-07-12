<?php
namespace Kirill\Cash\Model;
class History extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'cashback_bonus_history';

    protected $_cacheTag = 'cashback_bonus_history';

    protected $_eventPrefix = 'cashback_bonus_history';

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
        $values = [];

        return $values;
    }
}
