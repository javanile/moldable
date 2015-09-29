<?php

##
require_once 'common.php';

/*\
 *  Update database record without load by id
\*/

## ---------
## WRONG WAY
## ---------

##
echo '<h2>WRONG WAY</h2>';

## 
$Id = 1;

##
$Item = Person::load($Id);

##
$Item->age = 31;

##
$Item->store();


## ---------
## RIGHT WAY
## ---------

##
echo '<h2>RIGHT WAY</h2>';

##
$Id = 1;

##
Person::update($Id, array('age' => 31));


