<?php
    namespace Api;

    use \Slim\App;
    use \Slim\Http\Request;
    use \Slim\Http\Response;
    use Api\Setting;

final class Api
{
    /**
     * @var App;
     */
    private $api;

    public function __construct()
    {
        $settings = Setting::get();
        $api = new App($settings);

        $this->api = $api;
    }

    public function get() : App
    {
        return $this->api;
    }
}
