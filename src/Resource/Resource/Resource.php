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

    public function getById(int $id)
    {
        $object = $this->database->getById($id);
        if (!is_null($object)) {
            return $this->output->withJson($object);
        }

        return $this->output->notFound();
    }
}
