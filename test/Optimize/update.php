<?php

/*\
 *  Update database record without load by id
\*/

## ---------
## WRONG WAY
## ---------

## 
$id = 100;

##
$Item = Person::load($id);

##
$Item->age = 31;

##
$Item->store();


## ---------
## RIGHT WAY
## ---------

##
$id = 100;

##
Person::update($id, array('age' => 31));


