<?php

namespace SergeyNezbritskiy\CustomCartPriceRuleCondition\Observer;

use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class FirstOrderConditionObserver
 * @package SergeyNezbritskiy\CustomCartPriceRuleCondition\Observer
 */
class FirstOrderConditionObserver implements ObserverInterface
{

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        /** @var DataObject $additional */
        $additional = $observer->getData('additional');
        $conditions = $additional->getData('conditions') ?: [];
        $conditions = array_merge_recursive($conditions, [
            $this->getCustomerFirsLetterCondition()
        ]);
        $additional->setData('conditions', $conditions);
    }

    /**
     * @return array
     */
    private function getCustomerFirsLetterCondition(): array
    {
        return [
            'label' => __('First Order'),
            'value' => \SergeyNezbritskiy\CustomCartPriceRuleCondition\Model\Rule\Condition\FirstOrder::class
        ];
    }

}