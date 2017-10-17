# javanile/moldable
[![StyleCI](https://styleci.io/repos/43810715/shield?branch=master)](https://styleci.io/repos/43810715)
[![Code Climate](https://codeclimate.com/github/javanile-bot/moldable/badges/gpa.svg)](https://codeclimate.com/github/javanile-bot/moldable)
[![Build Status](https://travis-ci.org/javanile-bot/moldable.svg?branch=master)](https://travis-ci.org/javanile-bot/moldable)
[![Test Coverage](https://codeclimate.com/github/javanile-bot/moldable/badges/coverage.svg)](https://codeclimate.com/github/javanile-bot/moldable/coverage)

Moldable is an abstraction layer to manage MySQL database 
with improved function to alter-state and manipulate database schema.
Moldable integrates a ORM class for manage persistent objects and adapt database schema

## Searcing for contributors :sunglasses:
We are looking for contributors (PHP lovers) that are passioned by ORM and Database worlds for:
 - Applying PSR code standard in working codebase and beautify the source files
 - Testing library to different framework like: Slim, Laravel, ZendFramenwork, etc...
 - Write and maintains updated the wiki sections https://github.com/javanile/moldable/wiki
 - Increase popularity of https://packagist.org/packages/javanile/moldable by different kind of promotions

*We guarantee all visibility and thanks for our contributors, many many stars and public reference in all blog posts and articles that talk about javanile/moldable*

## Install via composer
We recommend installing via composer, to install otherwise you will write a issue.
```
composer require javanile/moldable
```

## Get Started
 - [Moldable ORM](https://github.com/javanile/moldable/wiki/Moldable-ORM): Manage persistent object in your web application
 - [DB Manipulation](https://github.com/javanile/moldable/wiki/Work-with-Database): Work with Database through advanced scripting tools

## How to: Connect to database

```php
<?php
// library namespace 
use Javanile\Moldable\Database;

// initialize a database connection object 
$db = new Database([
    'host'     => 'localhost',
    'dbname'   => 'db_marketing',
    'username' => 'root',
    'password' => 'p4ssw0rd',
    'prefix'   => 'prefix_',
]);

// '$db' is ready to use for your manipulation
```

## How to: Create ORM class-model

```php
<?php
// library namespace 
use Javanile\Moldable\Storable;

// define ORM class-model
class Customer extends Storable 
{
    public $id = self::PRIMARY_KEY;
    public $name = '';
}

// instance empty object
// database tables and fields are automatic generated 
// or updated if change Customer class
$customer = new Customer();

// assign values
$customer->name = 'Franky Franco';

// now object persist on DB
$customer->store();
```

## How to: Create schema (update if exists) 

```php
<?php
// '$db' is pre-connected database object (follow: 'How to: Connect to database')

// apply method send queries to create 
// or align database to defined schema 
$db->apply([
    // customer table name
    'Customer' => [		
        // customer fields
        'id'     => $db::PRIMARY_KEY,	// define field as a primary key
        'name'   => '',			// empty string define field as VARCHAR	
        'points' => 0,			// 0 (zero) define field as INT(11)
        'born'   => $db::DATE,		// use to define as date field
        'bio'    => $db::TEXT,		// text for large string and contents
    ],
    // products table name
    'Products' => [
        // products fields		
        'id'    => $db::PRIMARY_KEY,	// define field as a primary key
        'name'  => '',			// empty string define field as VARCHAR	
        'price' => .0,			// for float number init field with point-zero ".0"	
    ],
]);
```

## Talk about
 - https://medium.com/@billmike1994/getting-started-with-moldable-an-orm-for-continuous-migration-d4be845b7c65
 - https://github.com/nazneen1/follow/wiki/Utilize-Javanile--php-tool-to-connect-any-database
 - https://www.reddit.com/r/PHP/comments/6jsm2d/the_only_php_mysql_orm_for_continuous_delivery/
 - https://www.reddit.com/r/PHP/comments/3okj7x/schemadb_a_modern_and_coincise_database/?ref=readnext_4
 - https://www.reddit.com/r/PHP/comments/427zvg/schemadb_adapt_schema_of_mysql_db_based_on_class/
 - http://fudforum.org/forum/index.php?S=Google%20%5BBot%5D&t=msg&th=123561
 - http://www.codingforums.com/php/374551-manipulate-database-schema.html#post1497472
 - http://forums.phpfreaks.com/topic/300920-manipulate-database-schema-with-orm/
 - http://www.giorgiotave.it/forum/php-mysql/241550-manipolare-lo-schema-del-database.html#post1205019
 - http://www.iprogrammatori.it/forum-programmazione/php/manipolare-schema-del-database-t27275.html
 - http://ctolib.com/javanile-moldable.html

## Roadmap
 - Support to MongoDB for trasparent switch MySQL/MongoDB 
 - Manage table to store key-value pair like Setting or Config or MetaField
 - Manage UUID field (large integer or hash string) alternative to PRIMARY_KEY index
 - Flexible join system to extend field of table model on runtime
 - Define encode/decode static method for a sub-set of field 
 - Implementig Unit of work pattern
 - Listening For Query Events (gestione hook/event per modelli e query al db)
