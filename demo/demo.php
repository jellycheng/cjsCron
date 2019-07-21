<?php
/**
 * Created by PhpStorm.
 * User: jelly
 * Date: 2019/7/21
 * Time: 11:57
 */
require_once __DIR__ . '/common.php';

$range = range(0, 6);
var_export($range);
/**
    array (
        0 => 0,
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
    )
 */
echo PHP_EOL;

$fieldObj = new \CjsCron\FieldFactory();
$hourObj = $fieldObj->getField(1); //小时对象

var_export($hourObj);

