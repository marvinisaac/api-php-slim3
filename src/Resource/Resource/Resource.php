<?php

    namespace Resource;

    use Api\Shared\AdapterInterface\Database;
    use Api\Shared\AdapterInterface\Output;

final class Resource
{
    private $output;
    private $database;

    public function __construct(Database $database, Output $output)
    {
        $this->database = $database;
        $this->output = $output;
    }

    public function getAll()
    {
        $objectAll = $this->database->getAll();
        return $this->output->withJson($objectAll);
    }
}
