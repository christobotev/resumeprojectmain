<?php
namespace Docs\RestClientBundle\Client;

use GuzzleHttp\Message\Response;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Docs\RestClientBundle\Exception\ClientException;
use Docs\RestClientBundle\Client\ResultInterface;
use Docs\RestClientBundle\Serializer\ResponseSerializerInterface;

/**
 * Class that holds the result of a rest request to a service
 *
 * @author h.botev
 *
 */
class Result implements ResultInterface
{

    /**
     * The serever response object
     *
     * @var \GuzzleHttp\Message\Response
     */
    protected $response;

    /**
     * The serializer object that is used to decode the server response
     *
     * @var \Symfony\Component\Serializer\SerializerInterface
     */
    protected $serializer;

    /**
     * An array that contains the content from the "result" section of the
     * response body
     *
     * @var array
     */
    protected $parsedData;

    /**
     * The status from the "status" section of the response body
     *
     * @var string
     */
    protected $parsedStatus;

    /**
     * The count of the returned objects in cases when the
     * request was for a listing
     *
     * @var int
     */
    protected $parsedCount;

    /**
     * A list with response errors.
     * The errors are in
     * format ['message' => <string>, "humanRedable" => <bool>]
     *
     * @var array
     */
    protected $errors;

    /**
     * Flag indicating if the response is an error
     *
     * @var bool
     */
    protected $isError = false;

    /**
     * Initialize the result object and parse the response
     *
     * @param Response $response
     * @param SerializerInterface $serializer
     */
    public function __construct(Response $response, ResponseSerializerInterface $serializer)
    {
        $this->response = $response;

        $this->serializer = $serializer;

        $parsedResponse = $this->parseResponse($response);

        if (! empty($parsedResponse['result'])) {
            if (isset($parsedResponse['result']['count'])) {
                $this->parsedCount = $parsedResponse['result']['count'];
                unset($parsedResponse['result']['count']);
            }

            $this->parsedData = $parsedResponse['result'];
        }

        if (! empty($parsedResponse['status'])) {
            $this->parsedStatus = $parsedResponse['status'];
        }

        if ($response->getStatusCode() >= 400) {
            $this->isError = true;
            $errors = $this->collectErrorMessages($parsedResponse);
            $this->setErrorMessages($errors);
        }
    }

    /**
     * Return the content of the "result" section
     *
     * @return array
     */
    public function getData()
    {
        return $this->parsedData;
    }

    /**
     * Return the contnent of the "status" section
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->parsedStatus;
    }

    /**
     * Return the content of the count section
     * of listing responses
     *
     * @return int
     */
    public function getCount()
    {
        return $this->parsedCount;
    }

    /**
     * Return the server response object
     *
     * @return \GuzzleHttp\Message\Response
     */
    public function getRawResponse()
    {
        return $this->response;
    }

    /**
     * Return true if the server response was an error
     *
     * @return bool
     */
    public function isError()
    {
        return $this->isError;
    }

    /**
     * Return the list of error messages
     *
     * @return array
     */
    public function getErrorMessages()
    {
        return $this->errors;
    }

    /**
     * Set error massages.
     * Error messages can be set only
     * internaly by the current object
     *
     * @param array $errors
     * @return \Docs\RestClientBundle\Client\Result
     */
    protected function setErrorMessages(array $errors)
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * Parse the server response
     *
     * @param Response $response
     * @throws ClientException
     * @return array
     */
    protected function parseResponse(Response $response)
    {
        $contentType = $response->getHeader("Content-Type") ?: "application/xml";

        list ($contentType, ) = explode(";", $contentType);

        try {
            if (! $this->serializer->supportsDecoding($contentType)) {
                throw new ClientException("Unsupported content-type {$contentType}");
            }

            $parsedResponse = $this->serializer->decode(
                $response->getBody()->getContents(),
                $contentType
            );
        } catch (UnexpectedValueException $e) {
            throw new ClientException($e->getMessage(), $e->getCode(), $e);
        }

        return $parsedResponse;
    }

    /**
     * Collect error messages from the response body
     *
     * @param array $parsedResponse
     * @return array
     */
    protected function collectErrorMessages(array $parsedResponse)
    {
        $messages = $parsedResponse['message'];

        // single error message
        if (is_string($messages) || isset($messages['#'])) {
            return [
                $this->getStatus($messages)
            ];
        }

        // handle multiple errors
        $errors = [];
        foreach ($messages as $message) {
            $errors[] = $this->getStatus($message);
        }

        return $errors;
    }

    /**
     * Format a single error message
     *
     * @param string|array $message
     * @return array
     */
    protected function getSingleErrorData($message)
    {
        // non human readable error message
        if (is_string($message)) {
            return [
                "message" => $message,
                "humanReadable" => false
            ];
        }

        // human readable error message
        if (isset($message['#'])) {
            if (isset($message["@humanReadable"]) && $message["@humanReadable"] == 1) {
                return [
                    "message" => $message["#"],
                    "humanReadable" => true
                ];
            } else {
                return [
                    "message" => $message["#"],
                    "humanReadable" => false
                ];
            }
        }
    }
}
