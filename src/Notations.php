<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable;

interface Notations
{
    /**
     * Key notation
     */
    const KEY = '<<@primary_key>>';

    /**
     * Primary key notation
     */
    const PRIMARY_KEY = '<<@primary_key>>';

    /**
     *
     *
     */
    const PRIMARY_KEY_INT_20 = '<<@primary_key_int_20>>';
    
    /**
     *
     *
     */
    const VARCHAR = '<<{"Type":"varchar(255)"}>>';

    /**
     *
     *
     */
    const VARCHAR_10 = '<<{"Type":"varchar(10)"}>>';

    /**
     *
     *
     */
    const VARCHAR_32 = '<<{"Type":"varchar(32)"}>>';
    
    /**
     *
     *
     */
    const VARCHAR_64 = '<<{"Type":"varchar(64)"}>>';
    
    /**
     *
     *
     */
    const VARCHAR_128 = '<<{"Type":"varchar(128)"}>>';
    
    /**
     *
     *
     */
    const VARCHAR_255 = '<<{"Type":"varchar(255)"}>>';
    
    /**
     *
     *
     */
    const TEXT = '<<@text>>';
    
    /**
     *
     *
     */
    const TINYINT = '<<{"Type":"tinyint(4)"}>>';

    /**
     *
     *
     */
    const SMALLINT = '<<{"Type":"smallint(6)"}>>';

    /**
     *
     *
     */
    const MEDIUMINT = '<<{"Type":"mediumint(9)"}>>';
    
    /**
     *
     *
     */
    const INT = '<<@integer>>';

    /**
     *
     *
     */
    const INT_20 = '<<{"Type":"int(20)"}>>';

    /**
     *
     *
     */
    const BIGINT = '<<{"Type":"bigint(20)"}>>';
    
    /**
     *
     *
     */
    const DECIMAL = '<<{"Type":"decimal(10,2)"}>>';
    
    /**
     *
     *
     */
    const NUMERIC = '<<{"Type":"decimal(10,2)"}>>';

    /**
     *
     *
     */
    const REAL = '<<{"Type":"real"}>>';

    /**
     *
     *
     */
    const FLOAT = '<<@float>>';
    
    /**
     *
     *
     */
    const DOUBLE = '<<@double>>';

    /**
     * Define field as timestamp holder.
     *
     * @const string
     */
    const TIMESTAMP = '<<@timestamp>>';
    
    /**
     * Define field as time.
     *
     * @const string
     */
    const TIME = '00:00:00';

    /**
     * Define field as date.
     *
     * @const string
     */
    const DATE = '0000-00-00';
    
    /**
     * Define field as datetime.
     *
     * @const string
     */
    const DATETIME = '0000-00-00 00:00:00';

    /**
     * Define field as date.
     *
     * @const string
     */
    const YEAR = '<<{"Type":"int(4)"}>>';
}
