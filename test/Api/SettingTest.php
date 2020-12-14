<?php
    namespace Test\Api;

    use \PHPUnit\Framework\TestCase;
    use \Slim\App;
    use Api\Setting;

class SettingTest extends TestCase
{
    public function setUp() : void
    {
        $_ENV['PHP_ENVIRONMENT'] = 'TEST';
    }

    public function testSettingsShouldDisplayErrorDetailsInLocal() : void
    {
        $_ENV['PHP_ENVIRONMENT'] = 'LOCAL';
        $settings = Setting::get()['settings'];
        $this->assertTrue($settings['displayErrorDetails']);
    }
    
    public function testSettingsShouldDisplayErrorDetailsInTest() : void
    {
        $settings = Setting::get()['settings'];
        $this->assertTrue($settings['displayErrorDetails']);
    }

    public function testSettingsShouldNotDisplayErrorDetailsOtherwise() : void
    {
        $_ENV['PHP_ENVIRONMENT'] = 'PRODUCTION';
        $settings = Setting::get()['settings'];
        $this->assertFalse($settings['displayErrorDetails']);
    }
}
