<?php
namespace Docs\RestClientBundle\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Docs\RestClientBundle\EventListener\GuzzleListener;

/**
 * Data collector for the rest client bundle
 * 
 * @author h.botev
 *        
 */
class RestClientDataCollector extends DataCollector
{

    /**
     * Instance of the guzzle event subscriber
     * 
     * @var \Docs\RestClientBundle\EventListener\GuzzleListener
     */
    protected $guzzleListener;

    /**
     * Initailize the data collector and inject the instance
     * of the guzzle event subscriber
     * 
     * @param GuzzleListener $listener            
     */
    public function __construct(GuzzleListener $listener)
    {
        $this->guzzleListener = $listener;
    }

    /**
     * Set the profile data from the event subscriber to the
     * data array
     * 
     * @param Request $request            
     * @param Response $response            
     * @param \Exception $exception            
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['rest_client'] = $this->guzzleListener->getProfiles();
    }

    /**
     * Return the name of the collector
     * 
     * @return string
     */
    public function getName()
    {
        return "rest_client";
    }

    /**
     * Return the collector data
     * 
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
