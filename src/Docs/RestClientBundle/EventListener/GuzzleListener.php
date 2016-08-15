<?php
namespace Docs\RestClientBundle\EventListener;

use GuzzleHttp\Event\SubscriberInterface;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\ErrorEvent;
use GuzzleHttp\Event\AbstractRetryableEvent;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class that subscribes to guzzle events and
 * stores information about the http requests made by
 * the rest clients
 * 
 * @author h.botev
 *        
 */
class GuzzleListener implements SubscriberInterface
{

    /**
     * A list of profile information
     * 
     * @var array
     */
    protected $profiles = [];

    /**
     * Stopwatch instance
     * 
     * @var \Symfony\Component\Stopwatch\Stopwatch
     */
    protected $stopwatch;

    /**
     * Counter used for profiling
     * 
     * @var int
     */
    protected $count = 1;

    /**
     * Initailize the object and inject an instance of
     * the stopwatch
     * 
     * @param Stopwatch $stopwatch            
     */
    public function __construct(Stopwatch $stopwatch)
    {
        $this->stopwatch = $stopwatch;
    }

    /**
     * Return a list of the evenets for which
     * this class is subscribed
     * 
     * @return array
     */
    public function getEvents()
    {
        return [
            "before" => [
                "onBefore"
            ],
            "complete" => [
                "onComplete"
            ],
            "error" => [
                "onError"
            ]
        ];
    }

    /**
     * Execute before the guzzle request starts
     * 
     * @param BeforeEvent $ev            
     */
    public function onBefore(BeforeEvent $ev)
    {
        $request = $ev->getRequest();
        
        $body = $request->getBody();
        
        if ($body) {
            $bodyContents = $body->getContents();
            $body->seek(0);
        } else {
            $bodyContents = "";
        }
        
        $this->profiles[$this->count] = [
            "clientClass" => get_class($ev->getClient()),
            "requestUrl" => urldecode($request->getUrl()),
            "requestBody" => $bodyContents,
            "responseBody" => ""
        ];
        
        $this->stopwatch->start($this->count . "_restClient");
    }

    /**
     * Execute after the request is finished
     * 
     * @param CompleteEvent $ev            
     */
    public function onComplete(CompleteEvent $ev)
    {
        $this->handleRequestFinish($ev);
    }

    /**
     * Executed on request error
     * 
     * @param ErrorEvent $ev            
     */
    public function onError(ErrorEvent $ev)
    {
        $this->handleRequestFinish($ev);
    }

    /**
     * Return the list of profiles collected
     * 
     * @return array
     */
    public function getProfiles()
    {
        return $this->profiles;
    }

    /**
     * Handle "complete" and "error" events
     * 
     * @param AbstractRetryableEvent $ev            
     */
    protected function handleRequestFinish(AbstractRetryableEvent $ev)
    {
        $event = $this->stopwatch->getEvent($this->count . "_restClient");
        
        $response = $ev->getResponse();
        
        if ($response) {
            $this->profiles[$this->count]['responseBody'] = $response->getBody()->getContents();
            
            // reset the pointer of the response stream
            $response->getBody()->seek(0);
        }
        
        $this->profiles[$this->count]['time'] = $event->getDuration();
        
        $event->stop();
        
        ++ $this->count;
    }
}
