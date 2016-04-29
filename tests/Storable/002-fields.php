<?php

require_once 'common.php'; 

use Javanile\SchemaDB\Storable;

class Fields extends Storable
{    
    //
    static $config = [
        'StringType'     => 'text',
        'VarcharSize'    => 10,
        'PrimaryKeySize' => 12,
    ];
    
    // key
    public $id = self::PRIMARY_KEY;
    
    /* string * /
    public $string_0 = '';
    public $string_1 = 'sample string';
    public $string_2 = self::VARCHAR_32;
    public $string_3 = self::VARCHAR_64;
    public $string_4 = self::VARCHAR_128;
    public $string_5 = self::VARCHAR_255;
    public $string_6 = self::TEXT; /**/
    
    /* integer * /
    public $integer_0 = 0;
    public $integer_1 = 10;
    public $integer_2 = self::TINYINT;
    public $integer_3 = self::SMALLINT;
    public $integer_4 = self::MEDIUMINT;
    public $integer_5 = self::INT; 
    public $integer_6 = self::BIGINT; /**/

    /* integer * /
    public $float_0 = .0;
    public $float_1 = 1.1;
    public $float_2 = 10.0;
    public $float_3 = self::DECIMAL;
    public $float_4 = self::NUMERIC;
    public $float_5 = self::FLOAT; 
    public $float_6 = self::DOUBLE; 
    public $float_7 = self::DOUBLE; /**/

    /* date time * /
    public $datetime_0 = self::DATE;
    public $datetime_1 = self::DATETIME;
    public $datetime_2 = self::TIMESTAMP; /**/        
    /*_*/
}

//
#Fields::drop('confirm');

//
$db = Database::getDefault();

//
$db->setDebug(true);

//
$db->dump();
