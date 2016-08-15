#Docs Rest Client Bundle

Create a new client:
 * Create the new client class. The client class must extend Docs\RestClientBundle\Client\AbstractClient
 * Add configuration for the new client in the 'services' block of the container configuration. All rest clients must have tag 'rest_client' so the bundle could inject the default config arguments when the container is compiled.

Example config options:

```yml
services:
  rest_client.blabla:
      class: MyNamespace\MyBundle\Client\ExampleClient
      tags:
        - { name: rest_client }
      arguments:
        - base_url: https://api-hr.test.fb/
          resource: cities/api
          authenticationParams:
            - serviceID: 15
              apiKey: 3e65501d30764547fa63d8abf9094c19
          defaults:
            verify: '%path_to_certificate_here%'

By default the following additional configuration options are added to the arguments (these arguments are only added if they are not set in the service definition of the client):
* 'serializer' - instance of the rest_clinet.responseSerializer service. This is the service that is responsible for the decoding of the rest responses
* responseClass - Docs\RestClientBundle\Client\Result . This is the default class that will represent the rest responses and will be returned by the rest client's methods (findBy(), create(), update() and remove())
* collectRequests - %kernel.debug% . Flag indicating whether the client requests will be logged
* dataListener - instance of rest_client.dataListener. This object is the subscriber that listens to the guzzle events and collects debug data. It is injected in the data collector.

The AbstractClient extends Guzzle's Client class and the arguments passed to its constructor are passed to the parent constructor. For more infromation about the Guzzle clients, check the official docs at http://guzzle.readthedocs.org/en/latest/clients.html

### Example usage:
```php
$client = $this->container->get("rest_client.blabla");
try {
    $result = $client->findBy(["someParam" => 10]);
} catch (\Docs\RestClientBundle\Exception\OperationError $e) {
    var_dump($e->getResultObject()->getErrorMessages());
    die();
}

var_dump($result->getData());
```