<?php

class xmlParse {

    public $xmlFileName = 'xml_db.yal';

    public function parseXml($linkToXML) {
        $this->getRemoteXML($linkToXML);

        $offers = [];

        // Парсинг большого документа посредством XMLReader
        $reader = new XMLReader();
        $reader->open($this->xmlFileName);
        $flag = false;
        while ($reader->read()) {
            switch ($reader->nodeType) {
                case (XMLREADER::ELEMENT):
                    if ($reader->localName == "offer") {
                        $flag_img = true;
                        $flag = true;
                        if (!empty($res)) {
                            $offers['offers']['price'][$res['price']][] = $res;
                        }
                        $res = [];

                    }
                    if ($flag && $reader->localName != "offer") {
                        if ($reader->localName == 'image') {
                            $res[$reader->localName][] = $reader->readString();
                        } elseif ($reader->localName != 'image')  {
                            $res[$reader->localName] = $reader->readString();
                        }
                    }
            }
        }
        ksort($offers['offers']['price']);
        $pageCount = 1;
        $offerCount = 0;
        foreach ($offers['offers']['price'] as $offer) {
            foreach ($offer as $item) {
                $html = new htmlGenerate();
                $offers['offers'][$pageCount]['htmlOffers'] .= $html->offerGenerate($item, $pageCount, $offerCount);
                $offers['offers'][$pageCount]['offers'][$offerCount] = $item;
                $offers['marks'][$item['mark']][] = $item;
                $offers['marks'][$item['mark']]['htmlOffers'] .= $html->offerGenerate($item, $pageCount, $offerCount);
                $offers['marks'][$item['mark']]['models'][$item['model']][] = $item;
                $offers['marks'][$item['mark']]['models'][$item['model']]['htmlOffers'] .= $html->offerGenerate($item, $pageCount, $offerCount);
//
                $offerCount++;
                if ($offerCount %81 == 0) $pageCount++;
            }
        }

        return $offers;
    }

    public function getRemoteXML($linkToXML) {
        //Забираем XML с удаленного сервера
        if (!file_get_contents($this->xmlFileName)) {
            foreach ($linkToXML as $item) {
                $current = file_get_contents($item);
                if (file_put_contents($this->xmlFileName, $current, FILE_APPEND)) {
                    echo 'XML '.$item.' download success.'.PHP_EOL;
                } else {
                    echo 'Not download XML file';
                    die;
                }

            }
        }
    }
}