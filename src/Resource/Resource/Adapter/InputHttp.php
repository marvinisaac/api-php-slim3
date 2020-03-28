<?php

    namespace Resource\Adapter;

    use \Slim\Http\Request;
    use \Slim\Http\Response;
    use Api\Shared\AdapterInterface\Database;
    use Api\Shared\AdapterInterface\Input;
    use Resource\Resource;
    use Resource\Adapter\Mysql;
    use Resource\Adapter\OutputHttp as Output;

final class InputHttp implements Input
{
    private $database;

    public function __construct()
    {
        $this->database = new Mysql();
    }

    public function getAll(Request $request, Response $response, array $args) : Response
    {
        $output = new Output($response);
        $resource = new Resource($this->database, $output);
        return $resource->getAll();
    }

    public function getById(Request $request, Response $response, array $args) : Response
    {
        $output = new Output($response);
        $resource = new Resource($this->database, $output);
        return $resource->getById($args['id']);
    }
}
