<?php
    namespace Api;

    use \Slim\App;
    use \Slim\Http\Request;
    use \Slim\Http\Response;

final class Router
{
    public static function add(App $api) : App
    {
        $api->get('/health', function (Request $request, Response $response, array $args) {
            $response = $response->withStatus(200);
            $response = $response->withJson([
                'time' => date('Y-m-d H:i:s'),
            ]);
            return $response;
        });

        $api->any('/[{path:.*}]', function (Request $request, Response $response, array $args) {
            return $response->withStatus(404);
        });

        return $api;
    }
}
