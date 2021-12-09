<?php
/**
 * Copyright © Experius All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Experius\AlumioLog\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Settings extends AbstractHelper
{

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    protected $generalConfigPath = 'alumiolog/general/';

    const IS_ENABLED = 'is_enabled';
    const WEBSERVICE_URL = 'webservice_url';
    const WEBSERVICE_BEARER_TOKEN = 'webservice_bearer_token';
    const ALLOWED_ROUTES = 'allowed_routes';

    /**
     * @param ScopeConfigInterface
     * @param Context $context
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Context $context
    ) {
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    public function getIsEnabled()
    {
        return $this->scopeConfig->isSetFlag($this->generalConfigPath . self::IS_ENABLED);
    }

    /**
     * @return mixed
     */
    public function getWebserviceUrl()
    {
        return $this->scopeConfig->getValue($this->generalConfigPath . self::WEBSERVICE_URL);
    }

    /**
     * @return mixed
     */
    public function getWebserviceBearerToken()
    {
        return $this->scopeConfig->getValue($this->generalConfigPath . self::WEBSERVICE_BEARER_TOKEN);
    }

    /**
     * @return mixed
     */
    public function getAllowedRoutes()
    {
        return $this->scopeConfig->getValue($this->generalConfigPath . self::ALLOWED_ROUTES);
    }

    /**
     * @return bool
     */
    public function getWebserviceDisableSslCheck()
    {
        return true;
    }
}
