<?php



/*\
 * 
 * 
 * 
 * 
\*/
namespace SourceForge\SchemaDB;

/**
 * static part of sdbClass
 *
 *
 */
class ModelSchema extends ImprovedClass
{
	/**
	 * Instrospect and retrieve element schema
	 *  
	 * @return type
	 */
    protected static function getSchema()
    {		
		##
		$attribute = 'Schema';	

		##
		if (static::hasConfig($attribute)) {
			return static::getConfig($attribute);
		}
		
		##
		$fields = static::getSchemaFieldsWithValues();
		
        ##
        $schema = array();

        ##
        foreach ($fields as $name => $value) {
			$schema[$name] = $value;
        }
	
		##			
		SchemaParser::parseSchemaTable($schema);		
		
		##
		static::setConfig($attribute, $schema);
		
        ##
        return $schema;
    }
	
	/**
	 * Instrospect and retrieve element schema
	 *  
	 * @return type
	 */
    protected static function getSchemaFields()			
    {
		##
		$attribute	= 'SchemaFields';
		
		##
		$exclude	= 'SchemaExcludedFields';
		
		##
		if (static::hasConfig($attribute)) {
			return static::getConfig($attribute);
		}
		
		##
		$allFields = array_keys(get_class_vars(get_called_class()));
	
		##
		$fields = array_diff(
			$allFields, 
			static::getGlobal($exclude)
		);

		##
		if (static::hasConfig($exclude)) {
			$fields = array_diff(
				$fields, 
				static::getConfig($exclude)
			);
		}
		
		##
		static::setConfig($attribute, $fields);
		
		##
		return $fields; 		
	}
	
	/**
	 * Instrospect and retrieve element schema
	 *  
	 * @return type
	 */
    protected static function getSchemaFieldsWithValues()			
	{	
		##
		$attribute = 'SchemaFieldsWithValues';
		
		##
		$exclude = 'SchemaExcludedFields';
		
		##
		if (static::hasConfig($attribute)) {
			return static::getConfig($attribute);
		}
		
		##
		$fields = get_class_vars(static::getClass());
		
		##
		foreach(static::getGlobal($exclude) as $field) {
			unset($fields[$field]);
		}
		
		##
		if (static::hasConfig($exclude)) {
			foreach(static::getConfig($exclude) as $field) {
				unset($fields[$field]);
			}
		}
		
		##
		static::setConfig($attribute, $fields);
		
		##
		return $fields; 		
	}
	
    
	
}