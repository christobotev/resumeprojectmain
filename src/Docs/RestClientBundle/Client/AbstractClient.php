<?php
namespace Docs\RestClientBundle\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Message\Request;
use Docs\RestClientBundle\Builder\ConditionBuilderInterface;
use Docs\RestClientBundle\Builder\ConditionBuilder;
use Docs\RestClientBundle\Exception\ClientException;
use Docs\RestClientBundle\Exception\OperationError;
use Psr\Log\LoggerInterface;

abstract class AbstractClient extends Client
{
    /**
     * Condition builder instance for the current object
     *
     * @var ConditionBuilderInterface
     */
    protected $conditionBuilder;

    /**
     * The resource part of the request uri
     *
     * @var string
     */
    protected $resource;

    /**
     * The response serializer
     *
     * @var \Symfony\Component\Serializer\Serializer
     */
    protected $responseSerializer;

    /**
     * Flag indicating debug mode
     *
     * @var boolean
     */
    protected $debug = false;

    /**
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * The class that will wrap the returned results
     *
     * @var
     *
     */
    protected $responseClass = "\Docs\RestClientBundle\Client\Result";

    /**
     * Array with authentication params
     *
     * @var array
     */
    protected $authParams = [];

    /**
     * Initialize and configure the rest client
     *
     * @param array $config
     * @param ConditionBuilderInterface $queryBuilder
     * @throws ClientException
     */
    public function __construct(array $config = [], ConditionBuilderInterface $queryBuilder = null)
    {
        if (! isset($config['resource'])) {
            throw new ClientException('The resource name of the client is required');
        }

        if (! isset($config['serializer'])) {
            throw new ClientException('The serializer name of the client is required');
        }

        if (isset($config['responseClass'])) {
            $this->responseClass = $config['responseClass'];
        }

        if (isset($config['authenticationParams'])) {
            $this->authParams = $config['authenticationParams'];
        }

        if (isset($config['logger']) && $config['logger'] instanceof LoggerInterface) {
            $this->logger = $config['logger'];
        }

        if (isset($config['debug'])) {
            $this->debug = $config['debug'];
        }

        $this->resource = $config['resource'];
        $this->responseSerializer = $config['serializer'];

        parent::__construct($config);

        // if the query builder is not specified create an instance
        // of the default builder
        if ($queryBuilder) {
            $this->conditionBuilder = $queryBuilder;
        } else {
            $this->conditionBuilder = new ConditionBuilder();
        }

        // add event subscriber
        if (! empty($config['collectRequests'])) {
            $emitter = $this->getEmitter();
            $emitter->attach($config['dataListener']);
        }
    }

    /**
     * Send a GET request to the service with the given parameters
     *
     * @param array $params
     * @return \Docs\RestClientBundle\Client\ResultInterface
     */
    public function findBy(array $params = [])
    {
        $query = array_replace_recursive($this->authParams, $this->getQueryBuilder()->getQueryParams(), $params);
        $request = $this->createRequest(
            "GET",
            $this->resource,
            ["query" => $query]
        );

        return $this->sendRequest($request);
    }

    /**
     * Send a get request for a single resource
     *
     * @param int $id
     * @param array $params
     * @return \Docs\RestClientBundle\Client\ResultInterface
     */
    public function find($id, array $params = [])
    {
        $query = array_replace_recursive($this->authParams, $this->getQueryBuilder()->getQueryParams(), $params);
        $request = $this->createRequest(
            "GET",
            rtrim($this->resource, "/") . "/" . $id,
            ["query" => $query]
        );

        return $this->sendRequest($request);
    }

    /**
     * Send a POST request to the service
     *
     * @param array $data
     * @return \Docs\RestClientBundle\Client\ResultInterface
     */
    public function create(array $data)
    {
        $query = array_replace_recursive($this->authParams, $this->getQueryBuilder()->getQueryParams());

        $request = $this->createRequest(
            "POST",
            $this->resource,
            ["body" => $data,
            "query" => $query]
        );

        return $this->sendRequest($request);
    }

    /**
     * Send a PUT request to the service
     *
     * @param array $data
     * @return \Docs\RestClientBundle\Client\ResultInterface
     */
    public function update(array $data)
    {
        $query = array_replace_recursive($this->authParams, $this->getQueryBuilder()->getQueryParams());

        $request = $this->createRequest(
            "PUT",
            $this->resource,
            ["body" => $data,
            "query" => $query]
        );

        return $this->sendRequest($request);
    }

    /**
     * Send a DELETE request to the service
     *
     * @param int $id
     * @param array $params
     * @return \Docs\RestClientBundle\Client\ResultInterface
     */
    public function remove($id, array $params = [])
    {
        $query = array_replace_recursive(
            $this->authParams,
            $this->getQueryBuilder()->getQueryParams(),
            $params
        );

        $request = $this->createRequest(
            "DELETE",
            rtrim($this->resource, "/") . "/" . $id,
            ["query" => $query]
        );

        return $this->sendRequest($request);
    }

    /**
     * Return the current condition builder instance
     *
     * @return \Docs\RestClientBundle\Builder\ConditionBuilderInterface
     */
    public function getQueryBuilder()
    {
        return $this->conditionBuilder;
    }

    /**
     * Send request to the service
     *
     * @param Request $request
     * @throws \Docs\RestClientBundle\Exception\OperationError
     * @return \Docs\RestClientBundle\Client\ResultInterface
     */
    protected function sendRequest(Request $request)
    {
        try {
            if ($this->debug && $this->logger) {
                $this->logger->debug($request->getUrl());
            }

            $response = $this->send($request);
        } catch (RequestException $e) {

            if ($e->getResponse()) {
                $this->handleError($e->getResponse(), $e);
            }

            throw new ClientException("Request error", $e->getCode(), $e);
        }

        return new $this->responseClass($response, $this->responseSerializer);
    }

    /**
     * Handle error responses
     *
     * @param Response $response
     * @param \Exception $previousException
     * @throws \Docs\RestClientBundle\Exception\OperationError
     */
    protected function handleError(Response $response, \Exception $previousException = null)
    {
        $result = new $this->responseClass($response, $this->responseSerializer);

        $operationError = new OperationError("Operation failed.", 0, $previousException);

        $operationError->setServerResponse($response)->setResultObject($result);

        throw $operationError;
    }
}
