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
     * Key notation.
     */
    const KEY = '<<@primary_key>>';

    /**
     * Primary key notation.
     */
    const PRIMARY_KEY = '<<@primary_key>>';

    /**
     * Define field as timestamp holder.
     *
     * @const string
     */
    const PRIMARY_KEY_INT_20 = '<<@primary_key_int_20>>';

    /**
     * Define field as timestamp holder.
     *
     * @const string
     */
    const VARCHAR = '<<{"Type":"varchar(255)"}>>';

    /**
     * Define field as timestamp holder.
     *
     * @const string
     */
    const VARCHAR_10 = '<<{"Type":"varchar(10)"}>>';

    /**
     * Define field as timestamp holder.
     *
     * @const string
     */
    const VARCHAR_32 = '<<{"Type":"varchar(32)"}>>';

    /**
     * Define field as timestamp holder.
     *
     * @const string
     */
    const VARCHAR_64 = '<<{"Type":"varchar(64)"}>>';

    /**
     * Define field as timestamp holder.
     *
     * @const string
     */
    const VARCHAR_128 = '<<{"Type":"varchar(128)"}>>';

    /**
     * Define field as timestamp holder.
     *
     * @const string
     */
    const VARCHAR_255 = '<<{"Type":"varchar(255)"}>>';

    /**
     * Define field as timestamp holder.
     *
     * @const string
     */
    const TEXT = '<<@text>>';

    /**
     * Define field as timestamp holder.
     *
     * @const string
     */
    const TINYINT = '<<{"Type":"tinyint(4)"}>>';

    /**
     * Define field as timestamp holder.
     *
     * @const string
     */
    const SMALLINT = '<<{"Type":"smallint(6)"}>>';

    /**
     * Define field as timestamp holder.
     *
     * @const string
     */
    const MEDIUMINT = '<<{"Type":"mediumint(9)"}>>';

    /**
     * Define field as timestamp holder.
     *
     * @const string
     */
    const INT = '<<@integer>>';

    /**
     * Define field as timestamp holder.
     *
     * @const string
     */
    const INT_20 = '<<{"Type":"int(20)"}>>';

    /**
     * Define field as timestamp holder.
     *
     * @const string
     */
    const BIGINT = '<<{"Type":"bigint(20)"}>>';

    /**
     * Define field as timestamp holder.
     *
     * @const string
     */
    const DECIMAL = '<<{"Type":"decimal(10,2)"}>>';

    /**
     * Define field as timestamp holder.
     *
     * @const string
     */
    const NUMERIC = '<<{"Type":"decimal(10,2)"}>>';

    /**
     * Define field as timestamp holder.
     *
     * @const string
     */
    const REAL = '<<{"Type":"real"}>>';

    /**
     * Define field as timestamp holder.
     *
     * @const string
     */
    const FLOAT = '<<@float>>';

    /**
     * Define field as timestamp holder.
     *
     * @const string
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
