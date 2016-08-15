<?php
namespace Docs\RestClientBundle\Client;

interface ResultInterface
{

    /**
     * Return the content of the "result" section
     * 
     * @return array
     */
    public function getData();

    /**
     * Return the contnent of the "status" section
     * 
     * @return string
     */
    public function getStatus();

    /**
     * Return the content of the count section
     * of listing responses
     * 
     * @return int
     */
    public function getCount();

    /**
     * Return the server response object
     * 
     * @return \GuzzleHttp\Message\Response
     */
    public function getRawResponse();

    /**
     * Return true if the server response was an error
     * 
     * @return bool
     */
    public function isError();

    /**
     * Return the list of error messages
     * 
     * @return array
     */
    public function getErrorMessages();
}
