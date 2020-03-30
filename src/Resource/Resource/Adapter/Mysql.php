<?php

    namespace Resource\Adapter;
    
    use \Illuminate\Database\QueryException;
    use Api\Shared\AdapterInterface\Database;
    use Resource\Model\Resource;

final class Mysql implements Database
{
    public function readAll() : array
    {
        try {
            $objectAll = Resource::get();
            if (is_null($objectAll)) {
                return [
                    'success' => false,
                    'error_message' => 'No records found.',
                ];
            }

            return [
                'success' => true,
                'result' => $objectAll->toArray(),
            ];
        } catch (QueryException $e) {
            error_log('>>> MySQL error: ' . $e->getMessage());
            return [
                'success' => false,
                'error_message' => 'Database error.',
            ];
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
                return [
                    'success' => false,
                    'error_message' => 'No records found.',
                ];
            }

            return [
                'success' => true,
                'result' => $object->toArray(),
            ];
        } catch (QueryException $e) {
            error_log('>>> MySQL error: ' . $e->getMessage());
            return [
                'success' => false,
                'error_message' => 'Database error.',
            ];
        }
    }
    
    public function create(array $input) : array
    {
        try {
            Resource::create([
                'ordinal_position_long' => $input['ordinal_position_long'],
                'ordinal_position_short' => $input['ordinal_position_short'],
            ]);
            return [
                'success' => true,
            ];
        } catch (QueryException $e) {
            error_log('>>> MySQL error: ' . $e->getMessage());
            return [
                'success' => false,
                'error_message' => 'Database error.',
            ];
        }
    }
    
    public function update(int $id, array $input) : array
    {
        $invalidColumns = $this->checkColumns($input);
        if (count($invalidColumns) > 0) {
            return [
                'success' => false,
                'error_message' => 'Invalid columns: ' . json_encode($invalidColumns),
            ];
        }

        try {
            Resource::where('_id', $id)
                ->update($input);
            return [
                'success' => true,
            ];
        } catch (QueryException $e) {
            error_log('>>> Object update error.');
            error_log($e->getMessage());
            return [
                'success' => false,
                'error_message' => $e->getMessage(),
            ];
        }
    }

    private function checkColumns(array $input) : array
    {
        $databaseColumns = (new Resource())->getFillable();
        $inputColumns = array_keys($input);
        return array_diff($inputColumns, $databaseColumns);
    }
}
