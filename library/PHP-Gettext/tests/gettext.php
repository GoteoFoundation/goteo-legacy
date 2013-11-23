<?php

include_once "Gettext.php";

$dirname = realpath(dirname($_SERVER['SCRIPT_FILENAME']));
$gn = new Gettext_PHP($dirname . "/", "gettext", "de");
$ge = new Gettext_Extension($dirname . "/", "gettext", "de");
var_dump($gn->gettext("File does not exist"));
var_dump($ge->gettext("File does not exist"));
var_dump($gn->gettext("File does not exist") == $ge->gettext("File does not exist"));
var_dump($gn->ngettext("File is too small", "Files are too small", 1));
var_dump($ge->ngettext("File is too small", "Files are too small", 1));
var_dump($gn->ngettext("File is too small", "Files are too small", 1) == $ge->ngettext("File is too small", "Files are too small", 1));
var_dump($gn->ngettext("File is too small", "Files are too small", 2));
var_dump($ge->ngettext("File is too small", "Files are too small", 2));
var_dump($gn->ngettext("File is too small", "Files are too small", 2) == $ge->ngettext("File is too small", "Files are too small", 2));

