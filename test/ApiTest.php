<?php

    use \PHPUnit\Framework\TestCase;
    use \Slim\Http\Environment;
    use \Slim\Http\Headers;
    use \Slim\Http\Request;
    use \Slim\Http\RequestBody;
    use \Slim\Http\Uri;
    use Api\Api;

class AppTest extends TestCase
{
    protected $api;

    public function setUp() : void
    {
        $_ENV['PHP_ENVIRONMENT'] = 'TEST';
        $this->api = (new Api())->get();
    }

    public function testUnknownRouteShouldReturn404()
    {
        $request = $this->prepareRequest();
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(404, $responseStatus);
    }

    public function testDebugShouldReturnEnvironment()
    {
        $request = $this->prepareRequest('GET', '/debug/');
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseBody = json_decode((string)$response->getBody(), true);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(200, $responseStatus);
        $this->assertArrayHasKey('environment', $responseBody);
    }

    public function testGetResourceShouldReturnAllResources()
    {
        $request = $this->prepareRequest('GET', '/resource/');
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseBody = json_decode((string)$response->getBody(), true);
        $responseContentCount = count($responseBody);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(200, $responseStatus);
        $this->assertIsArray($responseBody);
        $this->assertGreaterThanOrEqual(1, $responseContentCount);
        $this->assertArrayHasKey('_id', $responseBody[0]);
        $this->assertArrayHasKey('ordinal_position_long', $responseBody[0]);
        $this->assertArrayHasKey('ordinal_position_short', $responseBody[0]);
    }

    public function testGetResourceByIdShouldReturnOnlyOneResource()
    {
        $request = $this->prepareRequest('GET', '/resource/1');
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseBody = json_decode((string)$response->getBody(), true);
        $responseContentCount = count($responseBody);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(200, $responseStatus);
        $this->assertIsArray($responseBody);
        $this->assertSame(1, $responseContentCount);
        $this->assertArrayHasKey('_id', $responseBody[0]);
        $this->assertArrayHasKey('ordinal_position_long', $responseBody[0]);
        $this->assertArrayHasKey('ordinal_position_short', $responseBody[0]);
    }

    public function testGetResourceByInvalidIdShouldReturn404()
    {
        $request = $this->prepareRequest('GET', '/resource/0');
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(404, $responseStatus);
    }

    public function testCreateResourceWithNoInputShouldReturn400()
    {
        $request = $this->prepareRequest('POST', '/resource/');
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(400, $responseStatus);
    }

    public function testCreateResourceWithMissingInputShouldReturn400()
    {
        $requestBody = [
            'ordinal_position_long' => 'n-th',
        ];
        $request = $this->prepareRequest('POST', '/resource/', $requestBody);
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(400, $responseStatus);
    }

    public function testCreateResourceWithValidInputShouldReturn201()
    {
        $requestBody = [
            'ordinal_position_long' => 'last',
            'ordinal_position_short' => 'nth',
        ];
        $request = $this->prepareRequest('POST', '/resource/', $requestBody);
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(201, $responseStatus);
    }

    public function testUpdateResourceWithNoInputShouldReturn400()
    {
        $request = $this->prepareRequest('PATCH', '/resource/0');
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(400, $responseStatus);
    }

    public function testUpdateResourceWithInvalidIdShouldReturn404()
    {
        $requestBody = [
            'ordinal_position_long' => 'last',
        ];
        $lastId = $this->getLastResourceId()['_id'];
        $invalidId = $lastId + 1;
        $request = $this->prepareRequest('PATCH', "/resource/$invalidId", $requestBody);
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(404, $responseStatus);
    }

    public function testUpdateResourceWithValidRequestShouldReturn204AndUpdateResource()
    {
        $requestBody = [
            'ordinal_position_long' => 'last_one',
        ];
        $lastId = $this->getLastResourceId()['_id'];

        $request = $this->prepareRequest('PATCH', "/resource/$lastId", $requestBody);
        $this->api->getContainer()['request'] = $request;
        $response = $this->api->run(true);
        $responseStatus = $response->getStatusCode();

        $request = $this->prepareRequest('GET', "/resource/$lastId");
        $this->api->getContainer()['request'] = $request;
        $modifiedResponse = $this->api->run(true);
        $modifiedResponseBody = json_decode((string)$modifiedResponse->getBody(), true);

        $this->assertSame(204, $responseStatus);
        $this->assertSame('last_one', $modifiedResponseBody[0]['ordinal_position_long']);
    }

    public function testDeleteResourceWithInvalidIdShouldReturn404()
    {
        $lastId = $this->getLastResourceId()['_id'];
        $invalidId = $lastId + 1;
        $request = $this->prepareRequest('DELETE', "/resource/$invalidId");
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(404, $responseStatus);
    }

    public function testDeleteResourceWithValidIdShouldReturn204AndDeleteResource()
    {
        $lastId = $this->getLastResourceId()['_id'];

        $request = $this->prepareRequest('DELETE', "/resource/$lastId");
        $this->api->getContainer()['request'] = $request;
        $response = $this->api->run(true);
        $responseStatus = $response->getStatusCode();
        
        $request = $this->prepareRequest('GET', "/resource/$lastId");
        $this->api->getContainer()['request'] = $request;
        $modifiedResponse = $this->api->run(true);
        $modifiedResponseStatus = $modifiedResponse->getStatusCode();

        $this->assertSame(204, $responseStatus);
        $this->assertSame(404, $modifiedResponseStatus);
    }

    private function prepareRequest(string $method = 'GET', string $uri = '/', array $requestBody = []) : Request
    {
        $environment = Environment::mock([
            'REQUEST_METHOD' => $method,
            'REQUEST_URI' => $uri,
        ]);

        if ($method === 'PATCH' ||
            $method === 'POST'
        ) {
            $environment = Environment::mock([
                'REQUEST_METHOD' => $method,
                'REQUEST_URI' => $uri,
                // Add required request header. Without it, request body ignored
                'CONTENT_TYPE' => 'application/json',
            ]);
        }

        $uri = Uri::createFromEnvironment($environment);
        $headers = Headers::createFromEnvironment($environment);
        $cookies = [];
        $serverParams = $environment->all();
        $body = new RequestBody();
        if (count($requestBody) > 0) {
            $body->write(json_encode($requestBody));
        }
        
        return new Request($method, $uri, $headers, $cookies, $serverParams, $body);
    }

    private function getLastResourceId() : array
    {
        $request = $this->prepareRequest('GET', '/resource/');
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseBody = json_decode((string)$response->getBody(), true);
        return array_pop($responseBody);
    }
}
