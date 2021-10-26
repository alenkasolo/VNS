<?php

namespace App\Repositories\ManyCreatable;

use DB;

class ManyCreatable implements IManyCreatable
{

    //return ids
    public function createMany(array $data, string $tableName): array
    {

        $keys = array_keys($data[0]);

        $query = "insert into ${tableName} (" . implode(', ', $keys) . ') values ';

        $values = [];
        for($i = 0; $i < count($data); $i++) {
            $currentRecord = $data[$i];
            $parameters = '';
            for ($j = 0; $j < count($currentRecord); $j++) {
                array_push($values, $currentRecord[$j]);
                if ($j === 0) {
                    $parameters .= '(';
                }
                $parameters.= '?';
                if ($j === (count($currentRecord) - 1)) {
                    $parameters .= ')';
                    break;
                }
                $parameters .= ', ';
            }

            if ($i < (count($data) - 1)) {
                $parameters .= ', ';
            }
            $query .= $parameters;
        }

        return DB::select(DB::raw($query . ' returning id'), $values);

    }
}
