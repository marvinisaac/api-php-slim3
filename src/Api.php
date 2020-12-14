<?php
    namespace Api;

    use \Slim\App;
    use \Slim\Http\Request;
    use \Slim\Http\Response;
    use Api\Setting;
    use Api\Router;

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

        $api = Router::add($api);

        $this->api = $api;
    }

    public function get() : App
    {
        return $this->api;
    }
}
