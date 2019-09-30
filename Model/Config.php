<?php

namespace Adele\OrderExport\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    const SKUS = 'orderexport/general/sku';
    const API_URL = 'orderexport/api/endpoint';
    const API_LOGIN = 'orderexport/api/login';
    const API_PASSWORD = 'orderexport/api/password';

    /**
     * @var array
     */
    private $skus;
    /**
     * @var string
     */
    private $apiUrl;
    /**
     * @var string
     */
    private $apiLogin;
    /**
     * @var string
     */
    private $apiPassword;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return array
     */
    public function getSkus()
    {
        if (null === $this->skus) {

            $this->skus = explode(',', $this->scopeConfig->getValue(self::SKUS));
        }

        return $this->skus;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        if (null === $this->apiUrl) {
            $this->apiUrl = $this->scopeConfig->getValue(self::API_URL);
        }
        return $this->apiUrl;
    }

    /**
     * @return string
     */
    public function getApiLogin()
    {
        if (null === $this->apiLogin) {
            $this->apiLogin = $this->scopeConfig->getValue(self::API_LOGIN);
        }
        return $this->apiLogin;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        if (null === $this->apiPassword) {
            $this->apiPassword = $this->scopeConfig->getValue(self::API_PASSWORD);
        }
        return $this->apiPassword;
    }
}
