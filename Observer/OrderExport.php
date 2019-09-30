<?php

namespace Adele\OrderExport\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Adele\OrderExport\Model\Export;

class OrderExport implements ObserverInterface
{
    /**
     * @var Export
     */
    private $export;

    /**
     * OrderExport constructor.
     *
     * @param Export $export
     */
    public function __construct(
        Export $export
    ) {
        $this->export = $export;
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $result = $this->export->run($order);
        /**
         * @TODO if anything wrong with export, order should be flagged to proccess export via cron
         *
         * if (!$result) {
         * }
         */
    }
}
