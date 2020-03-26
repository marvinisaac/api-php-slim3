<?php

    namespace Api;

    use \Slim\App;
    use \Slim\Http\Request;
    use \Slim\Http\Response;
    use Api\Setting;
    use Api\Router;

final class Api
{
    private $api;

    public function __construct()
    {
        $setting = Setting::get();
        $api = new App($setting);

        $api = Router::set($api);

        $this->api = $api;
    }

    public function get() : App
    {
        return $this->api;
    }
}
