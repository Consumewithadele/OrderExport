<?php

namespace Adele\OrderExport\Model;

use Adele\OrderExport\Model\Config;
use Magento\Framework\HTTP\Client\Curl;
use Psr\Log\LoggerInterface;

class Export
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var Curl
     */
    private $client;
    /**
     * @var LoggerInterface
     */
    private $log;

    /**
     * Export constructor.
     *
     * @param Config $config
     * @param Curl $client
     * @param LoggerInterface $log
     *
     */
    public function __construct(Config $config, Curl $client, LoggerInterface $log)
    {
        $this->config = $config;
        $this->client = $client;
        $this->log = $log;
    }

    /**
     * @param  \Magento\Sales\Api\Data\OrderInterface $order
     */
    public function run($order)
    {
        $data = $this->getOrderData($order);
        if (!$data) {
            return true;
        }
        return $this->sendApiRequest($data);
    }

    private function getOrderData($order)
    {
        $data = [];
        $data['items'] = [];
        $exportRequired = false;
        foreach ($order->getAllItems() as $item) {
            if (!in_array($item->getSku(), $this->config->getSkus())) {
                continue;
            }
            $exportRequired = true;
            $_item['sku'] = $item->getSku();
            $_item['qty'] = $item->getQtyOrdered();
            $_item['original_price'] = $item->getOriginalPrice();
            $_item['price'] = $item->getPrice();
            $_item['discount'] = $item->getDiscountAmount();
            $_item['total'] = $item->getRowTotal();
            $options = [];
            $itemOptions = $item->getProductOptions();
            if (isset($itemOptions['options'])) {
                foreach ($itemOptions['options'] as $option) {
                    $_option = [];
                    $_option['label'] = $option['label'];
                    $_option['value'] = $option['value'];
                    $options[] = $_option;
                }
            }
            $_item['options'] = $options;
            $data['items'][] = $_item;
        }

        if (!$exportRequired) {
            return $exportRequired;
        }

        $data['customer_id'] = $order->getCustomerIsGuest() ? null : $order->getCustomerId();
        $data['customer_name'] = $order->getCustomerFirstname() . ' ' . $order->getCustomerLastname();
        $data['customer_email'] = $order->getCustomerEmail();
        $billingAddress = $order->getBillingAddress();
        $shippingAddress = $order->getShippingAddress();
        $data['billing_address'] = [];
        $data['shipping_address'] = [];

        $data['billing_address']['telephone'] = $billingAddress->getTelephone();
        $data['billing_address']['country']   = $billingAddress->getCountryId();
        $data['billing_address']['postcode']  = $billingAddress->getPostcode();
        $data['billing_address']['region']    = $billingAddress->getRegion();
        $data['billing_address']['city']      = $billingAddress->getCity();
        $data['billing_address']['street']    = implode(',', $billingAddress->getStreet());

        $data['shipping_address']['telephone'] = $shippingAddress->getTelephone();
        $data['shipping_address']['country']   = $shippingAddress->getCountryId();
        $data['shipping_address']['postcode']  = $shippingAddress->getPostcode();
        $data['shipping_address']['region']    = $shippingAddress->getRegion();
        $data['shipping_address']['city']      = $shippingAddress->getCity();
        $data['shipping_address']['street']    = implode(',', $shippingAddress->getStreet());

        return $data;
    }

    private function sendApiRequest($data)
    {
        $this->client->addHeader('Content-Type', 'application/json');
        $this->client->setCredentials($this->config->getApiLogin(), $this->config->getPassword());
        try {
            $this->client->post($this->config->getApiUrl(), json_encode($data));
        } catch (\Exception $e) {
            $this->log->error($e->getMessage());
            return false;
        }
        if ($this->client->getStatus() === 200) {
            return true;
        } else {
            $this->log->error(__('Error during order export API call.'));
            return false;
        }
    }
}
