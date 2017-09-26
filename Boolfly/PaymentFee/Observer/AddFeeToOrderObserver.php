<?php

namespace Boolfly\PaymentFee\Observer;

use Boolfly\PaymentFee\Helper\Data;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class AddFeeToOrderObserver implements ObserverInterface
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /** @var Data  */
    protected $helper;

    /**
     * AddFeeToOrderObserver constructor.
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        Data $helper
    )
    {
        $this->_checkoutSession = $checkoutSession;
        $this->helper = $helper;
    }

    /**
     * Set payment fee to order
     *
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quote = $observer->getQuote();

        if ($this->helper->canApply($quote)) {
            $feeAmount = $this->helper->getFee($quote);

            //Set fee data to order
            $order = $observer->getOrder();
            $order->setData('fee_amount', $feeAmount);
            $order->setData('base_fee_amount', $feeAmount);
        }
        return $this;
    }
}
