<?php

    namespace Resource\Adapter;
    
    use \Illuminate\Database\QueryException;
    use Api\Shared\AdapterInterface\Database;
    use Resource\Model\Resource;

final class Mysql implements Database
{
    public function readAll() : ?array
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

    public function readById(int $id) : ?array
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
            error_log('>>> Object get by ID error.');
            error_log($e->getMessage());
            return null;
        }
    }
    
    public function create(array $input) : bool
    {
        try {
            Resource::create([
                'ordinal_position_long' => $input['ordinal_position_long'],
                'ordinal_position_short' => $input['ordinal_position_short'],
            ]);
            return true;
        } catch (QueryException $e) {
            error_log('>>> Object create error.');
            error_log(json_encode($e->errorInfo));
            return false;
        }
    }
}
