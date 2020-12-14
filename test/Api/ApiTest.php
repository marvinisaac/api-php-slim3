<?php
    namespace Test\Api;

    use \PHPUnit\Framework\TestCase;
    use \Slim\App;
    use Api\Api;

class ApiTest extends TestCase
{
    public function setUp() : void
    {
        $_ENV['PHP_ENVIRONMENT'] = 'TEST';
    }

    public function testApiShouldBeCreated() : void
    {
        $api = new Api();
        $api = $api->get();
        $this->assertInstanceOf(\Slim\App::class, $api);
    }
}
