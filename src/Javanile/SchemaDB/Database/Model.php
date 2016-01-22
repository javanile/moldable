<?php
/**
 *
 *
 */

namespace Javanile\SchemaDB\Database;

trait FieldApi
{       
    /**
     *
     * @param type $model
     */
    public function getPrimaryKey($model) {

        $table = $this->getTable($model);

        $desc = $this->descTable($table);
        
        foreach($desc as $field => $attributes) {
            
            foreach($attributes as $name => $value) {
                if ($name == 'Key' && $value == 'PRI') {
                    return $field;
                }
            }
        }

        return false;
    }

    /**
     *
     * @param type $model
     */
    public function getMainField($model) {

        $key = $this->getPrimaryKey($model);

        $table = $this->getTable($model);

        $desc = $this->descTable($table);
        
        foreach($desc as $field => $attributes) {
            
            if ($field == $key) {
                continue;
            }
            
            return $field;            
        }
    }

    /**
     *
     * @param type $model
     */
    public function getFields($model) {

        //
        $table = $this->getTable($model);

        //
        $desc = $this->descTable($table);

        //
        $fields = array_keys($desc);

        //
        return $fields;
    }
}