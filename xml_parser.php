<?php
error_reporting( E_ERROR );
require_once('lib/xmlParse.php');
require_once('lib/htmlGenerate.php');

$linkToXML = ["http://price.bmedia.ru/files/group-export/yandex/of_dealer_new.yal",
    "http://bb.bmedia.ru/files/export/groups/yandex/group_6784.yal",
    "http://price.bmedia.ru/files/group-export/yandex/zs_varshavka_new.yal",
    "http://price.bmedia.ru/files/group-export/yandex/zvezda_kashirka_all.yal",
    "http://price.bmedia.ru/files/group-export/yandex/rolf_piter.yal",
    "http://price.bmedia.ru/files/group-export/yandex/avtomobil.yal",
];

$xml = new xmlParse;
$html = new htmlGenerate();
$offers = $xml->parseXml($linkToXML);
$html->htmlStructureGen($offers);
echo 'index.html generate!';