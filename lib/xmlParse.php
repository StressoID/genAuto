<?php

class xmlParse {

    public $xmlFileName = 'xml_db.yal';

    public function parseXml($linkToXML) {
        $this->getRemoteXML($linkToXML);
        $offers = [];

        // Парсинг большого документа посредством XMLReader
        $reader = new XMLReader();
        $reader->open($this->xmlFileName);
        $pageCount = 1;
        $offerCount = 0;
        $flag = false;
        while ($reader->read()) {
            switch ($reader->nodeType) {
                case (XMLREADER::ELEMENT):
                    if ($reader->localName == "offer") {
                        $flag_img = true;
                        $flag = true;
                        if (!empty($res)) {
                            $html = new htmlGenerate();
                            $offers['marks'][$res['mark']][] = $res;
                            $offers['marks'][$res['mark']]['price'][$res['price']]['htmlOffers'] .= $html->offerGenerate($res, $pageCount, $offerCount);
                            $offers['marks'][$res['mark']]['models'][$res['model']][] = $res;
                            $offers['marks'][$res['mark']]['models'][$res['model']]['price'][$res['price']]['htmlOffers'] .= $html->offerGenerate($res, $pageCount, $offerCount);
//                            $offers['offers'][$pageCount]['htmlOffers'] .= $res['html'] = $html->offerGenerate($res, $pageCount, $offerCount);
//                            $offers['offers'][$pageCount]['offers'][$offerCount] = $res;
                            $offers['offers']['price'][$res['price']][] = $res;
                            if ($offerCount % 81 == 0) {
                                $pageCount++;
                            }
                        }
                        $res = [];
                        $offerCount++;

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
        $i = 1;
        foreach ($offers['marks'] as $mark => $value) {
            ksort($value['price']);
            foreach ($value['price'] as $item) {
                $offers['marks'][$mark]['htmlOffers'] .= $item['htmlOffers'];
            }
            foreach ($offers['marks'][$mark]['models'] as $model=> $value1) {
                ksort($value1['price']);
                foreach ($value1['price'] as $item) {
                    $offers['marks'][$mark]['models'][$model]['htmlOffers'] .= $item['htmlOffers'];
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
                $offerCount++;
            }
            if ($offerCount %81 == 0) $pageCount++;
        }

        return $offers;
    }

    public function getRemoteXML($linkToXML) {
        //Забираем XML с удаленного сервера
        if (!file_get_contents($this->xmlFileName)) {
            $current = file_get_contents($linkToXML);
            if (file_put_contents($this->xmlFileName, $current)) {
                echo 'XML file download success.'.PHP_EOL;
            } else {
                echo 'Not download XML file';
                die;
            }
        }
    }
}