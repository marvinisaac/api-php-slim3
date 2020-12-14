<?php
    namespace Test\Api;

    use \PHPUnit\Framework\TestCase;
    use \Slim\App;
    use Api\Api;
    use Test\Helper;

class RouterTest extends TestCase
{
    /**
     * @var App;
     */
    private $api;
    /**
     * @var Helper;
     */
    private $helper;

    public function setUp() : void
    {
        $_ENV['PHP_ENVIRONMENT'] = 'TEST';
        $api = new Api();
        $this->api = $api->get();
        $this->helper = new Helper();
    }

    public function testUnknownRoutesShouldReturn404() : void
    {
        $routes = [
            'random',
            'missing',
        ];
        foreach ($routes as $route) {
            $request = $this->helper->prepareRequest('GET', '/' . $route);
            $this->api->getContainer()['request'] = $request;

            $response = $this->api->run(true);
            $responseStatus = $response->getStatusCode();
            $this->assertSame(404, $responseStatus);
        }
    }

    public function testHealthCheckRouteShouldReturn200AndTime() : void
    {
        $request = $this->helper->prepareRequest('GET', '/health');
        $this->api->getContainer()['request'] = $request;

        $response = $this->api->run(true);
        $responseStatus = $response->getStatusCode();
        $responseBody = json_decode($response->getBody(), true);
        $this->assertSame(200, $responseStatus);
        $this->assertIsArray($responseBody);
        $this->assertArrayHasKey('time', $responseBody);
        // Date in Y-m-d H:i:s pattern
        $pattern = '/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/';
        $this->assertMatchesRegularExpression($pattern, $responseBody['time']);
    }
}
