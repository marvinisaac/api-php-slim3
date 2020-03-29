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

    private function prepareRequest(string $method = 'GET', string $uri = '/', array $requestBody = []) : Request
    {
        $environment = Environment::mock([
            'REQUEST_METHOD' => $method,
            'REQUEST_URI' => $uri,
        ]);

        if ($method === 'POST') {
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
}
