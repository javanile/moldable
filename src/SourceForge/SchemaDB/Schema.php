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
class Schema extends Table
{
		/**
	 * Instrospect and retrieve element schema
	 *  
	 * @return type
	 */
    public static function getSchemaFields()			
    {	
		##
		if (static::hasClassSetting('schemaFields')) {
			return static::getClassSetting('schemaFields');
		}
		
		##
		$allFields = array_keys(get_class_vars(get_called_class()));
	
		##
		$fields = array_diff(
			$allFields, 
			static::getModelSetting('schemaExcludedFields')
		);

		##
		if (static::hasClassSetting('schemaExcludedFields')) {
			$fields = array_diff(
				$fields, 
				static::getModelSetting('schemaExcludedFields')
			);
		}
		
		##
		static::setClassSetting('schemaFields', $fields);
		
		##
		return $fields; 		
	}
	
	/**
	 * Instrospect and retrieve element schema
	 *  
	 * @return type
	 */
    public static function getSchemaFieldsWithValues()			
	{	
		##
		if (static::hasClassSetting('schemaFieldsWithValues')) {
			return static::getClassSetting('schemaFieldsWithValues');
		}
		
		##
		$fields = get_class_vars(get_called_class());
		
		##
		foreach(static::getModelSetting('schemaExcludedFields') as $field) {
			unset($fields[$field]);
		}
		
		##
		if (static::hasClassSetting('schemaExcludedFields')) {
			foreach(static::getClassSetting('schemaExcludedFields') as $field) {
				unset($fields[$field]);
			}
		}
		
		##
		static::setClassSetting('schemaFieldsWithValues', $fields);
		
		##
		return $fields; 
		
	}
	
    /**
	 * Instrospect and retrieve element schema
	 *  
	 * @return type
	 */
    public static function getSchema()
    {		
		##
		if (static::hasClassSetting('schema')) {
			return static::getClassSetting('schema');
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
		Parser::parseSchemaTable($schema);		
		
		##
		static::setClassSetting('schema', $schema);
		
        ##
        return $schema;
    }

	
}