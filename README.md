# SchemaDB

SchemaDB is an abstraction layer to manage MySQL database 
with improved function to alter-state and manipulate database schema

# How to: Connect to database

```php
<?php
// library namespace 
use Javanile\SchamaDB;

// initialize a database connection object 
$db = new SchemaDB\Database(array(
	'host' => 'localhost',
	'user' => 'root',
	'pass' => 'p4ssw0rd',
	'name' => 'db_marketing',
	'pref' => 'prefix_',
));

// '$db' is ready to use for your manipulation
```

# How to: Create schema (update if exists) 

```php
<?php
// '$db' is pre-connected database object (follow: 'How to: Connect to database')

// apply method send queries to create 
// or align database to defined schema 
$db->apply(array(

	// customer table name
	'Customer' => array(
		
		// customer fields
		'id' => $db::PRIMARY_KEY,	// define field as a primary key
		'name' => '',				// empty string define field as VARCHAR	
		'points' => 0,				// 0 (zero) define field as INT(11)
		'born' => $db::DATE,		// use to define as date field
		'biography' => $db::TEXT,	// text for large string and contents
	),

	// products table name
	'Products' => array(

		// products fields		
		'id' => $db::PRIMARY_KEY,	// define field as a primary key
		'name' => '',				// empty string define field as VARCHAR	
		'price' => .0,				// for float number init field with point-zero ".0"	
 	),
));

```

# Roadmap

 - Manage table to store key-value pair like Setting or Config or MetaField
 - Manage UUID field (large integer or hash string) alternative to PRIMARY_KEY index
 - Flexible join system to extend field of table model on runtime
 - Define encode/decode static method for a sub-set of field 
 - Implementig Unit of work pattern
 - Listening For Query Events (gestione hook/event per modelli e query al db)
 - [DONE!] Port to GitHub