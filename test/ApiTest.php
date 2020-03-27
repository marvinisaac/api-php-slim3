<?php

    use \PHPUnit\Framework\TestCase;
    use \Slim\Http\Environment as SlimEnvironment;
    use \Slim\Http\Request;
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
        $environment = SlimEnvironment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/',
        ]);
        $request = Request::createFromEnvironment($environment);
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(404, $responseStatus);
    }

    public function testDebugShouldReturnEnvironment()
    {
        $environment = SlimEnvironment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/debug/',
        ]);
        $request = Request::createFromEnvironment($environment);
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseBody = json_decode((string)$response->getBody(), true);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(200, $responseStatus);
        $this->assertArrayHasKey('environment', $responseBody);
    }

    public function testExampleEndpointShouldReturnHelloWorld()
    {
        $environment = SlimEnvironment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/resource/',
        ]);
        $request = Request::createFromEnvironment($environment);
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseBody = json_decode((string)$response->getBody(), true);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(200, $responseStatus);
        $this->assertArrayHasKey('hello', $responseBody);
        $this->assertSame('world', $responseBody['hello']);
    }
}
