<?php

/*\
 * 
 * 
 * 
 * 
\*/
namespace Javanile\SchemaDB;

/**
 * static part of sdbClass
 *
 *
 */
class SchemaNotation 
{
    /**
	 * schemadb mysql constants for rapid fields creation
	 */
    const PRIMARY_KEY			= '<<#primary_key>>';
    const PRIMARY_KEY_INT_20	= '<<#primary_key>>'; 
    
	/**
	 * 
	 */
	const VARCHAR		= '<<{"Type":"varchar(255)"}>>';
    const VARCHAR_32	= '<<{"Type":"varchar(32)"}>>';
    const VARCHAR_64	= '<<{"Type":"varchar(64)"}>>';
    const VARCHAR_128	= '<<{"Type":"varchar(128)"}>>';
    const VARCHAR_255	= '<<{"Type":"varchar(255)"}>>';
    const TEXT			= '<<{"Type":"text"}>>';
    
	/**
	 * 
	 * 
	 */
	const TINYINT	= '<<{"Type":"tinyint(4)"}>>';
    const SMALLINT	= '<<{"Type":"smallint(6)"}>>';
    const MEDIUMINT	= '<<{"Type":"mediumint(9)"}>>';
    const INT		= '<<{"Type":"int(11)"}>>';
    const INT_20	= '<<{"Type":"int(20)"}>>';
    const BIGINT	= '<<{"Type":"bigint(20)"}>>';
    
	/**
	 * 
	 */
	const DECIMAL	= '<<{"Type":"decimal(10,2)"}>>';
    const NUMERIC	= '<<{"Type":"decimal(10,2)"}>>';
    const REAL		= '<<{"Type":"real"}>>';
    const FLOAT		= '<<{"Type":"float"}>>';
    const DOUBLE	= '<<{"Type":"double"}>>';
    	
	/**
	 * 
	 */
	const TIME				= '00:00:00';
    const DATE				= '0000-00-00';
    const DATETIME			= '0000-00-00 00:00:00';
}