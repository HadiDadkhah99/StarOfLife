<?php

class RequestChecker
{


    public static function checkHeaderInputs(array $inputs, bool $checkEmptyValue = true, bool $printErr = true): ?bool
    {


        foreach ($inputs as $val) {

            //if is not set key
            if (!isset(getallheaders()[$val])) {
                if ($printErr) {
                    echo json_encode(['status' => 0, 'error_type' => __FUNCTION__ . "()", 'message' => "The var name ($val) is not set in Header"], JSON_UNESCAPED_UNICODE);
                    die();
                } else return false;
            } //if is set key
            else if (isset(getallheaders()[$val]) and $checkEmptyValue and empty(getallheaders()[$val])) {

                if ($printErr) {
                    echo json_encode(['status' => 0, 'error_type' => __FUNCTION__, 'message' => "The var name ($val) is empty in Header"], JSON_UNESCAPED_UNICODE);
                    die();
                } else return false;

            }

        }

        return true;

    }


    public static function checkGetInputs(array $inputs, bool $checkEmptyValue = true, bool $printErr = true): ?bool
    {

        foreach ($inputs as $val) {

            //if is not set key
            if (!isset($_GET[$val])) {
                if ($printErr) {
                    echo json_encode(['status' => 0, 'error_type' => __FUNCTION__ . "()", 'message' => "The var name ($val) is not set in GET request"], JSON_UNESCAPED_UNICODE);
                    die();
                } else return false;
            } //if is set key
            else if (isset($_GET[$val]) and $checkEmptyValue and empty($_GET[$val])) {

                if ($printErr) {
                    echo json_encode(['status' => 0, 'error_type' => __FUNCTION__, 'message' => "The var name ($val) is empty in GET request"], JSON_UNESCAPED_UNICODE);
                    die();
                } else return false;

            }

        }


        return true;

    }


    public static function checkPostInputs(array $inputs, bool $checkEmptyValue = true, bool $printErr = true): ?bool
    {

        foreach ($inputs as $val) {

            //if is not set key
            if (!isset($_POST[$val])) {

                if ($printErr) {
                    echo json_encode(['status' => 0, 'error_type' => __FUNCTION__ . "()", 'message' => "The var name ($val) is not set in POST request"], JSON_UNESCAPED_UNICODE);
                    die();
                } else return false;

            } //if is set key
            else if (isset($_POST[$val]) and $checkEmptyValue and empty($_POST[$val])) {

                if ($printErr) {
                    echo json_encode(['status' => 0, 'error_type' => __FUNCTION__, 'message' => "The var name ($val) is empty in POST request"], JSON_UNESCAPED_UNICODE);
                    die();
                } else return false;

            }

        }

        return true;

    }


}