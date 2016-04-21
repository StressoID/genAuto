<?php

class htmlGenerate {
    protected $mark_menu;
    protected $mark_section;
    protected $model_section;
    protected $header = 'htmlSource/header.html';
    protected $footer = 'htmlSource/footer.html';

    public function htmlStructureGen($html) {
        $this->siteFolderGen();
        $countPage = count($html['offers']) - 1;
        foreach ($html['offers'] as $num => $page) {
            var_dump($num);
            if ($num == 1) {
                $this->indexGenerate($page['htmlOffers'], $countPage, $html['marks']);
                foreach ($page['offers'] as $id => $offer) {
                    $this->detailGen($num, $id, $offer);
                }

            } else {
                $this->pagPageGenerate($page['htmlOffers'], $countPage, $num, $html['marks']);
                foreach ($page['offers'] as $id => $offer) {
                    $this->detailGen($num, $id, $offer);
                }
            }
        }
        if (count($html['marks']) > 0) {
            foreach ($html['marks'] as $mark => $value) {
                if (mkdir('site/'.strtolower($mark))) {
                    echo 'mark folder '.$mark.' created'.PHP_EOL;
                    $this->markPageGen($mark, $html['marks'], $value['htmlOffers']);
                }
            }
        }
        $this->gen404();

    }

    public function modelPageGen($mark, $model, $models, $model_html) {
        $header = file_get_contents($this->header);
        $footer = file_get_contents($this->footer);
        $h2 = $this->genHeader($model);
        $pagination = $this->pagGenerate();

        if (file_put_contents('site/'.strtolower($mark).'/'.strtolower($model).'/index.html', $header.$this->mark_menu.$h2.$model_html.$pagination.$footer)) {
            return true;
        }
    }
    public function markPageGen($mark, $marks, $mark_html) {
        $header = file_get_contents($this->header);
        $footer = file_get_contents($this->footer);
        $h2 = $this->genHeader($mark);
        $pagination = $this->pagGenerate();
        $this->model_section = $this->sectionModelsGen($mark, $marks[$mark]['models']);
        if (count($marks[$mark]['models']) > 0) {
            foreach ($marks[$mark]['models'] as $model => $value_model) {
                if (mkdir('site/' . strtolower($mark).'/'.strtolower($model))) {
                    echo 'model_folder ' . $model . ' created' . PHP_EOL;
                    $this->modelPageGen($mark, $model, $marks[$mark]['models'], $value_model['htmlOffers']);
                }
            }
        }

        if (file_put_contents('site/'.strtolower($mark).'/index.html', $header.$this->mark_menu.$this->model_section.$h2.$mark_html.$pagination.$footer)) {
            return true;
        }
    }

    public function indexGenerate ($offersHtml, $countPage, $marks) {
        $header = file_get_contents($this->header);
        $footer = file_get_contents($this->footer);
        $h2 = $this->genHeader('Главная');
        $pagination = $this->pagGenerate($countPage);
        ksort($marks);
        $this->mark_menu = $this->markMenuGen($marks);
        $this->mark_section = $this->sectionMarkGen($marks);

        if (file_put_contents('site/index.html', $header.$this->mark_menu.$this->mark_section.$h2.$offersHtml.$pagination.$footer)) {
            return true;
        }

    }

    public function pagPageGenerate($offersHtml, $countPage, $pagNum, $marks) {
        $nameFolder = 'p'.$pagNum;
        mkdir('site/'.$nameFolder, 0777, true);
        $header = file_get_contents($this->header);
        $footer = file_get_contents($this->footer);
        $h2 = $this->genHeader('Каталог');
        $pagination = $this->pagGenerate($countPage);

        if (file_put_contents('site/'.$nameFolder.'/index.html', $header.$this->mark_menu.$this->mark_section.$h2.$offersHtml.$pagination.$footer)) {
            return true;
        }
    }

    public function sectionMarkGen($marks) {
        $sectionMark = '<div id="sectionMark" class="col-sm-6 col-md-4"><div class="widget widget-boxed widget-boxed-dark">
                        <div class="list-group">';
        $i = 0;
        $sectCount = '';
        foreach ($marks as $mark => $value) {
            if (($i % ceil(count($marks)/3)) == 0 && $i != 0 && $sectCount < 2) {
                $sectCount++;
                $sectionMark .= '</div></div></div><div id="sectionMark" class="col-sm-6 col-md-4"><div class="widget widget-boxed widget-boxed-dark"><div class="list-group"><a href="/site/' . strtolower($mark) . '/index.html" class="list-group-item">
                                <i class="fa fa-angle-right"></i>' . $mark . '</a>';
            } else {
                $sectionMark .= '<a href="/site/' . strtolower($mark) . '/index.html" class="list-group-item">
                                <i class="fa fa-angle-right"></i>' . $mark . '</a>';
            }
            $i++;
        }
        $sectionMark .= '</div></div><!-- /.list-group -->
                   </div><!-- /.widget --></div></nav></div><!-- /#header-inner --></div><!-- /#header --></div><!-- /#header-wrapper --><div id="main-wrapper">
        <div id="main">
            <div id="main-inner">
                <div class="container">
                    <div class="block-content">
                        <div class="block-content-inner">';

        return $sectionMark;
    }
    public function sectionModelsGen($mark, $models) {
        $sectionModel = '<div id="sectionMark" class="col-sm-6 col-md-4"><div class="widget widget-boxed widget-boxed-dark">
                        <div class="list-group">';
        $i = 0;
        $col = 1;
        ksort($models);

        foreach ($models as $model => $value) {
            if (($i % ceil(count($models)/3)) == 0 && count($models) >= 3 && $i != 0 && $col < 3) {
                $col++;
                $sectionModel .= '</div></div></div><div id="sectionMark" class="col-sm-6 col-md-4"><div class="widget widget-boxed widget-boxed-dark"><div class="list-group"><a href="/site/' .strtolower($mark).'/'. strtolower($model) . '/index.html" class="list-group-item">
                                <i class="fa fa-angle-right"></i>' . $model . '</a>';
            } else {
                $sectionModel .= '<a href="/site/' .strtolower($mark).'/'. strtolower($model) . '/index.html" class="list-group-item">
                                <i class="fa fa-angle-right"></i>' . $model . '</a>';
            }
            $i++;
        }
        $sectionModel .= '</div></div><!-- /.list-group -->
                   </div><!-- /.widget --></div></nav></div><!-- /#header-inner --></div><!-- /#header --></div><!-- /#header-wrapper --><div id="main-wrapper">
        <div id="main">
            <div id="main-inner">
                <div class="container">
                    <div class="block-content">
                        <div class="block-content-inner">';

        return $sectionModel;
    }

    public function pagGenerate($countPage = false) {
        $pag = '</div><!-- /.row -->
                </div>
                </div><!-- /.row -->
                </div><!-- /.boxes -->
                <div class="center">
            <ul class="pagination">';
        if ($countPage !== false) {
            for ($i = 1; $i <= $countPage; $i++) {
                if ($i == 1) {
                    $pag .= '<li id="p'.$i.'"><a href="/site/index.html">'.$i.'</a></li>';
                } else {
                    $pag .= '<li id="p'.$i.'"><a href="/site/p'.$i.'/index.html">'.$i.'</a></li>';
                }
            }
        }
        $pag .= '</ul>
        </div><!-- /.center -->';
        return $pag;
    }

    public function siteFolderGen() {
        if (!file_exists('site')) {
            mkdir('site');
        } else {
            echo 'Directory "site" already exist - rf rf......';
            shell_exec('rm -rf site');
            mkdir('site');
        }
        shell_exec('cp -rv htmlSource site');
        shell_exec('cp -v .htaccess site');
        shell_exec('ln -s site/index.html index.html');
    }

    public function offerGenerate($offer, $pageCount, $offerCount) {
        return $offer = '<div class="col-sm-6 col-md-4">
            <div class="box background-white">
                <div class="box-picture">
                    <a href="/site/p'.$pageCount.'/'.$offerCount.'.html">
                        <img height="250" src="'.(($offer['image'][0]) ?: '../htmlSource/nophoto4.png' ).'" alt="">
                        <span></span>
                    </a>
                </div><!-- /.box-picture -->

                <div class="box-body">
                    <h2 class="box-title">
                        <a href="/site/p'.$pageCount.'/'.$offerCount.'.html">'.$offer['mark'].' '.$offer['model'].'</a>
                    </h2><!-- /.box-title -->

                    <div class="box-content">
                        <dl class="dl-horizontal">
                            <dt class="odd">Цена</dt>
                            <dd class="odd price">'.number_format($offer['price'],0, '', ' ').' '.$offer['currency-type'].'</dd>
                            <dt>Пробег</dt>
                            <dd>'.$offer['run'].' km</dd>
                            <dt class="odd">Цвет</dt>
                            <dd class="odd">'.$offer['color'].'</dd>
                            <dt>Телефон</dt>
                            <dd><a href="tel:'.$offer['seller-phone'].'">'.$offer['seller-phone'].'</a></dd>
                        </dl>
                    </div><!-- /.box-content -->
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>';
    }

    public function genHeader($h2) {
        $header = '<h2 class="mb40">'.$h2.'</h2>
            <div class="boxes">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">';
        return $header;
    }

    public function markMenuGen($marks) {
        ksort($marks);
        $menu = '<ul><li><ul>';
        $i = 1;
        foreach ($marks as $mark => $value) {
            if (($i % ceil(count($marks)/3)) == 0) {
                $menu .= '</ul></li><li><ul>';
            } else {
                $menu .= '<li><a href="/site/'.strtolower($mark).'/index.html">'.$mark.'</a></li>';
            }
            $i++;
        }
        $menu .= '</ul></li></ul><!-- /.nav --></div><!-- /.navbar-collapse -->';
        return  $menu;
    }

    public function detailGen($num, $id, $offer) {
        sort($offer['image']);
        $nameFolder = 'p'.$num;
        mkdir('site/'.$nameFolder, 0777, true);
        $header = file_get_contents('htmlSource/detail_header.html');
        $footer = file_get_contents('htmlSource/detail_footer.html');

        $detail = '<h1 class="widgetized-title">'.$offer['mark'].' '.$offer['model'].'</h1>
                    <div class="row">
                        <div class="col-sm-6">
                            <div id="gallery-wrapper">
                                <div class="gallery">';
        if ($offer['image'][0] == '') {
            $detail .= '<div class="slide active">
                            <div class="picture-wrapper">
                                <img src="../htmlSource/nophoto4.png" alt="#">
                            </div>
                        </div>';
        } else {
            foreach ($offer['image'] as $k => $image) {
                $detail .= '<div class="slide' . (($k == 0) ? ' active' : '') . '">
                                    <div class="picture-wrapper">
                                        <img src="' . $image . '" alt="#">
                                    </div>
                                </div>';
                if ($k == 4) break;
            }
            $detail .= '</div><!-- /.gallery -->

                        <div id="gallery-pager" class="background-white">
                            <div class="prev">
                                <i class="fa fa-angle-left"></i>
                            </div>

                            <div class="pager">
                            </div>

                            <div class="next">
                                <i class="fa fa-angle-right"></i>
                            </div>
                        </div><!-- /#gallery-pager -->


                        <div class="gallery-thumbnails">';
            foreach ($offer['image'] as $k => $image) {
                $detail .= '<div class="thumbnail-' . $k . '">
                                <img src="' . $image . '" alt="#">
                            </div>';
                if ($k == 4) break;
            }
        }
        $detail .= '</div><!-- /.gallery-thumbnails -->
                    </div> <!-- /#gallery-wrapper -->';
        $detail .='</div>

                <div class="col-sm-6">
                    <ul id="myTab" class="nav nav-tabs three">
                        <li class="active"><a href="#overview" data-toggle="tab">Характеристики</a></li>
                        <li><a href="#appliances" data-toggle="tab">Комплектация</a></li>
                    </ul>

                    <div id="myTabContent" class="tab-content">
                        <div class="tab-pane fade active in" id="overview">
                            <div class="row">
                                <div class="col-sm-7 col-md-12">
                                    <table class="table table-attributes">
                                        <tbody>
                                        <tr>
                                            <td class="property">Цена</td>
                                            <td class="value">'.number_format($offer['price'],0, '', ' ').'</td>
                                        </tr>
                                        <tr>
                                            <td class="property">Салон</td>
                                            <td class="value">'.$offer['seller'].'</td>
                                        </tr>
                                        <tr>
                                            <td class="property">Город</td>
                                            <td class="value">'.$offer['seller-city'].'</td>
                                        </tr>
                                        <tr>
                                            <td class="property">Тип</td>
                                            <td class="value">'.$offer['state'].'</td>
                                        </tr>

                                        <tr>
                                            <td class="property">Модель</td>
                                            <td class="value">'.$offer['model'].'</td>
                                        </tr>

                                        <tr>
                                            <td class="property">Двигатель</td>
                                            <td class="value">'.$offer['engine-type'].'</td>
                                        </tr>

                                        <tr>
                                            <td class="property">Мощность</td>
                                            <td class="value">'.$offer['horse-power'].'</td>
                                        </tr>

                                        <tr>
                                            <td class="property">Объем</td>
                                            <td class="value">'.$offer['displacement']/1000 .'</td>
                                        </tr>


                                        <tr>
                                            <td class="property">КПП</td>
                                            <td class="value">'.$offer['transmission'].'</td>
                                        </tr>

                                        <tr>
                                            <td class="property">Год</td>
                                            <td class="value">'.$offer['year'].'</td>
                                        </tr>
                                        <tr>
                                            <td class="property">Пробег</td>
                                            <td class="value">'.$offer['run'].' '. $offer['run-metric'].'</td>
                                        </tr>

                                        <tr>
                                            <td class="property">Цвет</td>
                                            <td class="value">'.$offer['color'].'</td>
                                        </tr>

                                        <tr>
                                            <td class="property">Телефон</td>
                                            <td class="value"><a href="tel:'.$offer['seller-phone'].'">'.$offer['seller-phone'].'</a></td>
                                        </tr>
                                        </tbody>
                                    </table><!-- /.table -->
                                </div><!-- /.col-md-7 -->
                            </div><!-- /.row -->
                        </div><!-- /.tab-pane -->

                        <div class="tab-pane fade" id="appliances">
                            <div class="row">
                                <div class="col-sm-6 col-md-12">
                                    <p>'.$offer['additional-info'].'</p>
                                    <!-- /.appliances -->
                                </div><!-- /.col-md-6 -->
                            </div><!-- /.row -->
                        </div><!-- /.tab-pane -->
                    </div>
                </div>
            </div><!-- /.row -->';
        if (file_put_contents('site/'.$nameFolder.'/'.$id.'.html', $header.$this->mark_menu.$this->mark_section.$detail.$footer)) {
            return true;
        }
    }

    public function gen404() {
        $header = file_get_contents('htmlSource/detail_header.html');
        $footer = file_get_contents('htmlSource/detail_footer.html');
        $html ='
            <div class="hero-title center">
                404
            </div>

            <div class="hero-subtitle center">
                Page Not Found
            </div>';
        if (file_put_contents('site/404.html', $header.$this->mark_menu.$this->mark_section.$html.$footer)) {
            return true;
        }
    }
}