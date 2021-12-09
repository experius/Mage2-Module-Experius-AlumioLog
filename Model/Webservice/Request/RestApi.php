<?php

namespace Experius\AlumioLog\Model\Webservice\Request ;

use Experius\AlumioLog\Helper\Settings;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\App\State;
use Psr\Log\LoggerInterface;

/**
 * Class RestApi
 * @package Experius\OculusWebserviceClient\Model\Webservice\Request
 */
class RestApi
{

    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @var State
     */
    protected $appState;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * RestApi constructor.
     * @param LoggerInterface $logger
     * @param Settings $settings
     * @param State $appState
     */
    public function __construct(
        LoggerInterface $logger,
        Settings $settings,
        State $appState
    ) {
        $this->logger = $logger;
        $this->settings = $settings;
        $this->appState = $appState;
    }

    /**
     * @param $url
     * @param array $dataArray
     * @param string $postType
     * @param int $timeout
     * @return bool|mixed
     * @throws GuzzleException
     */
    public function call($url, $dataArray = [], $postType = "GET", $timeout = 8)
    {
        $this->validateConfig();

        $options = [
            'connect_timeout' => 10,
            'timeout' => $timeout,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->settings->getWebserviceBearerToken(),
                'Accept' => 'application/json'
            ]
        ];

        if ($this->settings->getWebserviceDisableSslCheck()) {
            $options['verify'] = false;
        }

        if ($postType == 'GET' && !empty($dataArray)) {
            $options['query'] = $dataArray;
        } elseif (in_array($postType, ['POST', 'PUT', 'DELETE']) && !empty($dataArray)) {
            $options['body'] = json_encode($dataArray);
        }

        try{
            $client = new \GuzzleHttp\Client([
                'base_uri' => $this->settings->getWebserviceUrl(),
            ]);
            $response = $client->request($postType, ltrim($url, '/'), $options);
        } catch (\Exception $e) {
            $this->logger->error($e);
            return false;
        }

        $code = $response->getStatusCode();
        $body = (string)$response->getBody();

        if ($code == '200') {
            return json_decode($body, true);
        } elseif ($code == '202') {
            return true;
        } elseif ($code >= 300) {
            $this->logger->error("Get New AlumioLogs failed with statuscode {$code} \n This body was returned: {$body}");
            return false;
        }

        return false;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    protected function validateConfig()
    {
        $missingConfig = array();
        if(!$this->settings->getIsEnabled()){
            $missingConfig[] = 'is_enabled';
        }
        if (!$this->settings->getWebserviceUrl()){
            $missingConfig[] = 'url';
        }
        if (!$this->settings->getWebserviceBearerToken()){
            $missingConfig[] = 'bearertoken';
        }
        if (!empty($missingConfig)){
            throw new \Exception('One or more config values are missing: ' . implode(', ', $missingConfig));
        }
        return true;
    }

}
