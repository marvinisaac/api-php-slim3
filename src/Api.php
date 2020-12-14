<?php
    namespace Api;

    use \Slim\App;
    use \Slim\Http\Request;
    use \Slim\Http\Response;

final class Api
{
    /**
     * @var App;
     */
    private $api;

    public function __construct()
    {
        $api = new App();

        $this->api = $api;
    }

    public function get() : App
    {
        return $this->api;
    }
}
