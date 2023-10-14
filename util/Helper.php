<?php


/**
 * Convert Array Object to DataModel Object
 * @param $array
 * @param $toClass
 * @return mixed
 * @throws ReflectionException
 */
function arrayObjToClassObj($array, $toClass)
{


    $object = new $toClass();

    $reflectionClass = new ReflectionClass($toClass->name());

    foreach ($array as $key => $value) {
        try {
            $reflectionClass->getProperty($key)->setValue($object, $value);
        } catch (Exception $e) {
        }
    }

    return $object;

}

/**
 * Convert Json Object to DataModel Object
 * @param $jsonObj
 * @param $toClass
 * @return mixed
 * @throws ReflectionException
 */
function jsonObjToClassObj($jsonObj, $toClass)
{
    $jsonObj = json_encode($jsonObj, JSON_UNESCAPED_UNICODE);
    //convert to array
    $array = json_decode($jsonObj, true);

    return arrayObjToClassObj($array, $toClass);

}