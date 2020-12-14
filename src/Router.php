<?php
    namespace Api;

    use \Slim\App;
    use \Slim\Http\Request;
    use \Slim\Http\Response;

final class Router
{
    public static function add(App $api) : App
    {
        return $api;
    }
}
