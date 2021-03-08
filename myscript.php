<?php


$errorArray = array(
    'ERROR_X_NULL'    => 'Поле X не может быть пустым',
    'ERROR_X_STR'     => 'Поле X должно соделжать только числа',
    'ERROR_X_BORDERS' => 'Значение X не удовлеторяет промежутку',
    'OK_X'            => 'Все окей X',
    'ERROR_Y_NULL'    => 'Вы не выбрали значение Y',
    'OK_Y'            => 'Все окей Y',
    'ERROR_R_NULL'    => 'Поле R не может быть пустым',
    'ERROR_R_STR'     => 'Поле R должно соделжать только числа',
    'ERROR_R_BORDERS' => 'Значение R не удовлеторяет промежутку',
    'OK_R'            => 'Все окей R'
);

$x = $_POST["cordinateX"];
$r = $_POST["radiusR"];
$y = $_POST["cordinateY"];
$timeNow = $_POST["timeNow"];

function areaOne($x, $y, $r) {
    return sqrt($x*$x + $y*$y) <= $r;
}

function areaTwo($x, $y, $r) {
    return $x <= 0 && $y >= 0 &&
        $y <= $x + ($r/2);
}

function areaThree($x, $y, $r) {
    if (abs($x) < abs($r) && abs($y) < abs($r)) return true;
    return false;
}

function areaOn($x, $y, $r) {
    if ($x == 0) return abs($y) <= abs($r);
    return abs($x) <= abs($r);
}

function validateX($x, $errorArray) {
    if ($x == "") {
        return $errorArray['ERROR_X_NULL'];
    }else if (!is_numeric($x)) {
        return $errorArray['ERROR_X_STR'];
    }else if ($x > -5 && $x < 3) {
        return $errorArray['OK_X'];
    }
    return $errorArray['ERROR_X_BORDERS'];
}

function validateY($y, $errorArray) {
    if ($y != 'undefined' || $y == 0){
        return $errorArray['OK_Y'];
    }
    return $errorArray['ERROR_Y_NULL'];
}

function validateR($r, $errorArray) {
    if ($r == "") {
        return $errorArray['ERROR_R_NULL'];
    }else if (!is_numeric($r)) {
        return $errorArray['ERROR_R_STR'];
    }else if ($r > 2 && $r < 5) {
        return $errorArray['OK_R'];
    }
    return $errorArray['ERROR_R_BORDERS'];
}

function validate($x, $y, $r, $errorArray) {
    $resultX = validateX($x, $errorArray);
    if ($resultX != 'Все окей X') return $resultX;
    $resultY = validateY($y, $errorArray);
    if ($resultY != 'Все окей Y') return $resultY;
    $resultR = validateR($r, $errorArray);
    if ($resultR != 'Все окей R') return $resultR;
    if (checkArea($x, $y, $r)) {
        return 'Есть пробитие';
    }
    return 'Нету пробития';
}



function checkArea($x, $y, $r)
{
    if ($x > 0 && $y > 0) return areaOne($x, $y, $r);
    elseif ($x < 0 && $y > 0) return areaTwo($x, $y, $r);
    elseif ($x < 0 && $y < 0) return areaThree($x, $y, $r);
    elseif ($x > 0 && $y < 0) return false;
    else return areaOn($x, $y, $r);
}

$result = checkArea($x, $y, $r);
$stringResult = $result ? "true" : "false";

$currentTime = date('H:i:s', time()-$timeNow*60);
$executionTime = round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 7);

$x = round(str_replace(",", ".", $x), 5);
$y = round(str_replace(",", ".", $y), 5);
$r = round(str_replace(",", ".", $r), 5);

$testString = validate($x, $y, $r, $errorArray);

$jsonData = "";
$str_error = "-";
//$x = str_replace(",", ".", $x);
if ($testString == 'Есть пробитие' || $testString == 'Нету пробития') {
    $jsonData = '{' .
        "\"cordinateX\":\"$x\"," .
        "\"cordinateY\":\"$y\"," .
        "\"radiusR\":\"$r\"," .
        "\"timeLol\":\"$currentTime\"," .
        "\"timeLong\":\"$executionTime\"," .
        "\"itog\":\"$testString\"" .
//    "\"itog\":$stringResult" .
        "}";
}else {
    $jsonData = '{' .
        "\"cordinateX\":\"$str_error\"," .
        "\"cordinateY\":\"$str_error\"," .
        "\"radiusR\":\"$str_error\"," .
        "\"timeLol\":\"$currentTime\"," .
        "\"timeLong\":\"$executionTime\"," .
        "\"itog\":\"$testString\"" .
//    "\"itog\":$stringResult" .
        "}";
}
echo $jsonData;


