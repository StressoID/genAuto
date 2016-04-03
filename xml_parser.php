<?php
error_reporting( E_ERROR );
require_once('lib/xmlParse.php');
require_once('lib/htmlGenerate.php');

$linkToXML = "http://price.bmedia.ru/files/group-export/yandex/avtomobil.yal";

$xml = new xmlParse;
$html = new htmlGenerate();
$offers = $xml->parseXml($linkToXML);
$html->htmlStructureGen($offers);
echo 'index.html generate!';