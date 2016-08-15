<?php
namespace Docs\MainBundle\Google;


class GoogleClient
{
    /**
     * @var \Google_Client
     */
    protected $client;

    public function __construct(GoogleClientConfig $config)
    {
        $googleClient = new \Google_Client($config->getConfig());
        $this->setClient($googleClient);
    }

    /**
     * @return Google_Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set Google_Client
     * @param Google_Client $client
     */
    protected function setClient(\Google_Client $client)
    {
        $this->client = $client;
    }
}