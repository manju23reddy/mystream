<?php
require 'autoload.php';
include 'Configs.php';

use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseACL;
use Parse\ParsePush;
use Parse\ParseUser;
use Parse\ParseInstallation;
use Parse\ParseException;
use Parse\ParseAnalytics;
use Parse\ParseFile;
use Parse\ParseCloud;
use Parse\ParseClient;
use Parse\ParseSessionStorage;
use Parse\ParseGeoPoint;
session_start();

/*
// REQUIRE HTTPS
if ($_SERVER['HTTPS'] != "on") {
    $url = "https://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    header("Location: $url");
    exit;
}
*/

// TIME AGO SINCE DATE -------------------------
function time_ago($date) {
    if (empty($date)) {
        return "No date provided";
    }
    $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths = array("60", "60", "24", "7", "4.35", "12", "10");
    $now = time();
    $unix_date = strtotime($date);
// check validity of date
    if (empty($unix_date)) {
        return "Bad date";
    }
// is it future date or past date
    if ($now > $unix_date) {
        $difference = $now - $unix_date;
        $tense = "ago";
    } else {
        $difference = $unix_date - $now;
        $tense = "from now";
    }
    for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
        $difference /= $lengths[$j];
    }
    $difference = round($difference);
    if ($difference != 1) {
        $periods[$j].= "s";
    }
    return "$difference $periods[$j] {$tense}";
}

// Round large numbers into KMGT
function roundNumbersIntoKMGT($n) {
  $n = (0+str_replace(",","",$n));
  if(!is_numeric($n)) return false;
  if($n>1000000000000) return round(($n/1000000000000),1).'T';
  else if($n>1000000000) return round(($n/1000000000),1).'G';
  else if($n>1000000) return round(($n/1000000),1).'M';
  else if($n>1000) return round(($n/1000),1).'K';
  return number_format($n);
}

echo '
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

<title>My Stream | A place for your creativity</title>

<!-- Favicons -->
<link rel="apple-touch-icon" href="assets/img/apple-icon.png">
<link rel="icon" href="assets/img/favicon.png">

<!-- Fonts and icons     -->
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />

<!-- Material Kit CSS -->
<link rel="stylesheet" href="assets/css/material-kit.css?v=2.0.0">

<!-- Magnific css -->
<link href="assets/css/magnific-popup.css" rel="stylesheet">

<!-- Custom Audio player -->
<script type="text/javascript" src="assets/js/audio.js"></script>

</head>
';
?>