<?php

    namespace Resource\Adapter;
    
    use \Illuminate\Database\QueryException;
    use Api\Shared\AdapterInterface\Database;
    use Resource\Model\Resource;

final class Mysql implements Database
{
    public function getAll() : ?array
    {
        try {
            $objectAll = Resource::get();
            if (is_null($objectAll)) {
                return null;
            }
            return $objectAll->toArray();
        } catch (QueryException $e) {
            error_log('>>> Object get all error.');
            error_log($e->getMessage());
            return null;
        }
    }

    public function getById(int $id) : ?array
    {
        try {
            $object = Resource::where('_id', $id)
                ->get();
            if (is_null($object) ||
                count($object) === 0
            ) {
                return null;
            }
            return $object->toArray();
        } catch (QueryException $e) {
            error_log('>>> Object get all error.');
            error_log($e->getMessage());
            return null;
        }
    }
}
