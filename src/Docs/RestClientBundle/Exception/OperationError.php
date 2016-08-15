<?php
namespace Docs\RestClientBundle\Exception;

use GuzzleHttp\Message\Response;
use Docs\RestClientBundle\Client\ResultInterface;

/**
 * Exception thrown when the rest client makes a request to
 * a service and the service returns an error
 * 
 * @author h.botev
 *        
 */
class OperationError extends Exception
{

    /**
     * The response from the server
     * 
     * @var \GuzzleHttp\Message\Response
     */
    protected $response;

    protected $resultObject;

    /**
     * Set the response object from the request
     * 
     * @param Response $response            
     * @return \Docs\RestClientBundle\Exception\OperationError
     */
    public function setServerResponse(Response $response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * Get the response object
     * 
     * @return \GuzzleHttp\Message\Response
     */
    public function getServerResponse()
    {
        return $this->response;
    }

    /**
     * Set the result object
     * 
     * @param ResultInterface $resultObj            
     * @return \Docs\RestClientBundle\Exception\OperationError
     */
    public function setResultObject(ResultInterface $resultObj)
    {
        $this->resultObject = $resultObj;
        return $this;
    }

    /**
     * Return the result object
     * 
     * @return \Docs\RestClientBundle\Client\ResultInterface
     */
    public function getResultObject()
    {
        return $this->resultObject;
    }
}
