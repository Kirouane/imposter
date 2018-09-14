<?php


$a = new stdClass();
$a->test = function() {

};

var_dump(serialize($a));