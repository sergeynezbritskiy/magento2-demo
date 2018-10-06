<?php

namespace SergeyNezbritskiy\CustomCartPriceRuleCondition\Model\Rule\Condition;

/**
 * Class FirstOrder
 * @package SergeyNezbritskiy\CustomCartPriceRuleCondition\Model\Rule\Condition
 */
class FirstOrder extends \Magento\Rule\Model\Condition\AbstractCondition
{

    /**
     * @var \Magento\Config\Model\Config\Source\Yesno
     */
    protected $sourceYesNo;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $orderFactory;

    /**
     * Constructor
     * @param \Magento\Rule\Model\Condition\Context $context
     * @param \Magento\Config\Model\Config\Source\Yesno $sourceYesno
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Rule\Model\Condition\Context $context,
        \Magento\Config\Model\Config\Source\Yesno $sourceYesno,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderFactory,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->sourceYesNo = $sourceYesno;
        $this->orderFactory = $orderFactory;
    }

    /**
     * Load attribute options
     * @return $this
     */
    public function loadAttributeOptions()
    {
        return $this->setData('attribute_option', [
            'customer_first_order' => __('Customer First Order')
        ]);
    }

    /**
     * Get input type
     * @return string
     */
    public function getInputType()
    {
        return 'select';
    }

    /**
     * Get value element type
     * @return string
     */
    public function getValueElementType()
    {
        return 'select';
    }

    /**
     * Get value select options
     * @return array|mixed
     */
    public function getValueSelectOptions()
    {
        if (!$this->hasData('value_select_options')) {
            $this->setData('value_select_options', $this->sourceYesNo->toOptionArray());
        }
        return $this->getData('value_select_options');
    }

    /**
     * Validate Customer First Order Rule Condition
     * @param \Magento\Framework\Model\AbstractModel $model
     * @return bool
     */
    public function validate(\Magento\Framework\Model\AbstractModel $model)
    {
        $customerId = $model->getCustomerId();
        $order = $this->orderFactory->create()
            ->addAttributeToSelect('customer_id')
            ->addFieldToFilter('customer_id', ['eq' => $customerId])
            ->getFirstItem();

        $firstOrder = 1;
        if ($order->getId()) {
            $firstOrder = 0;
        }
        $model->setData('customer_first_order', $firstOrder);
        return parent::validate($model);
    }

}