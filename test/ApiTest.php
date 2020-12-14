<?php
    namespace Test;

    use \PHPUnit\Framework\TestCase;
    use \Slim\App;
    use Api\Api;
    use Test\ApiTestHelper as Helper;

class ApiTest extends TestCase
{
    public function testApiShouldBeCreated() : void
    {
        $api = new Api();
        $api = $api->get();
        $this->assertInstanceOf(\Slim\App::class, $api);
    }
}
