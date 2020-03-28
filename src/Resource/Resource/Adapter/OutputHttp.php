<?php

    namespace Resource\Adapter;

    use \Slim\Http\Response;
    use Api\Shared\AdapterInterface\Output;

final class OutputHttp implements Output
{
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function withJson(array $output) : Response
    {
        return $this->response->withJson($output);
    }

    public function notFound() : Response
    {
        return $this->response->withStatus(404);
    }
}
