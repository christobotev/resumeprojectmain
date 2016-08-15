<?php
namespace Docs\MainBundle\Google;

/**
 * Class that holds data
 * for google client creation
 * @author hbotev
 */
class GoogleClientConfig
{
    /**
     * @var string
     */
    const APPLICATION_NAME = 'ResumeSampleProject';

    /**
     * google client config array
     * @var array
     */
    protected $clientConfig = [];

    /**
     * @param string $developersKey
     * @param string $clientID
     * @param string $clientSecret
     */
    public function __construct($developersKey, $clientID, $clientSecret)
    {
        $this->clientConfig['application_name'] = self::APPLICATION_NAME;
        $this->clientConfig['developer_key'] =$developersKey;
        $this->clientConfig['cache_config'] = [];
        $this->clientConfig['client_id'] = $clientID;
        $this->clientConfig['client_secret'] = $clientSecret;
        $this->clientConfig['redirect_uri'] = '';
        $this->clientConfig['use_objects'] = true;
    }

    /**
     * Returns the config array for
     * creating a google client
     */
    public function getConfig()
    {
        return $this->clientConfig;
    }
}