<?php

/**
 * Created by PhpStorm.
 * User: myindexlike
 * Date: 09.03.2016
 * Time: 3:37
 */
class sitemap
{
    const COUNTINFILE = 5000;

    private $folder;
    private $parent;

    function __construct($parent,$folder='xml') {
        $this->parent=$parent;
        $this->path_folder=MODX_BASE_PATH.$folder.'/';
        $this->url_folder=MODX_SITE_URL.$folder.'/';

    }


    /**
     * Создаем файл xml по родителю
     *
     * @return bool|string
     */
    public function creatingXML (){
        global $modx;
        $list = array();
        $mes ='';
        // получаем количество товаров
        $count = $this->CountChildren ($this->parent);

        //echo '$count='.$count;

        if ($count!=FALSE){
            $iteration = ceil($count/self::COUNTINFILE); // получаем количество итераций - файлов
            for ($x=0; $x<$iteration; $x++) {
                $list=$this->getURLlist($this->parent, $x*self::COUNTINFILE, self::COUNTINFILE);
                if (count($list)>0){
                    $li='';
                    foreach ($list as $key => $value) {
                        $li .= implode('', $value);
                    }
                    // var_dump($list);
                    $str ='<?xml version="1.0" encoding="UTF-8"?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.$li.'</urlset>';

                    echo $str;
                    $mes = $this->saveFile ($str,$this->parent.'-'.$x);
                }
            }
        }
        // return $mes;
    }


    /**
     * подсчитываем количество дочерних документов (прямых потомков)
     * @param $parents
     *
     * @return bool
     */
    private function CountChildren ($parents){
        global $modx;
        $result=$modx->db->query("SELECT count(*) as cc
                FROM  " . $modx->getFullTableName('site_content') . "
                WHERE `parent` in (".$parents.") and `deleted`=0 and `published`=1");
        while ($row=$modx->db->getRow($result)) {
            return $row['cc'];
        }
        return FALSE;
    }

    /**
     * Формируем список ссылок
     *
     * @param     $parents
     * @param int $start
     * @param int $kolvo
     *
     * @return array
     */
    function getURLlist ($parents, $start=0, $kolvo=self::COUNTINFILE, $k=0){
        global $modx;
        $k ++;
        $list = array();
        $result=$modx->db->query("SELECT `id`, `isfolder`, `parent`, `template`,
                                  FROM_UNIXTIME(`editedon`, '%Y-%m-%d') as lastmod
            FROM  " . $modx->getFullTableName('site_content') . "
            WHERE `parent` in (".$parents.") and `deleted`=0 and `published`=1 limit ".$start.", ".$kolvo." ");
        while ($row=$modx->db->getRow($result)) {
            $url = $modx->makeUrl($row['id'], '', '', 'full');
            $changefreq = $this->getChangefreq($row['template']);
            $priority = $this->getPriority($row['template'],$k);

            $list[$row['parent']][] ='<url><loc>'.$url.'</loc><lastmod>'.$row['lastmod'].'</lastmod>'.$changefreq.''.$priority.'</url>';

            if ($row['isfolder']==1){

                $list_dop = $this->getURLlist($row['id'], 0, self::COUNTINFILE, $k);
                if (is_array($list_dop)){
                    $list=array_merge($list, $list_dop);
                }
            }

        }
        return $list;
    }


    function getChangefreq($template){
        return '<changefreq>weekly</changefreq>';

    }

    function getPriority($template, $k){
        $p = 1;
        switch ($k) {
            case 0:
                $p='1';
                break;
            case 1:
                $p='0.9';
                break;
            case 2:
                $p='0.8';
                break;
            case 3:
                $p='0.7';
                break;
            case 4:
                $p='0.6';
                break;
        }
        return '<priority>'.$p.'</priority>';

    }

    /**
     * @param $list
     * @param $parentid
     *
     * @return string
     */
    function saveFile ($list,$parentid){

        $fp = fopen($this->path_folder.$parentid.'.xml', 'w');

        // var_dump($this->path_folder.$parentid.'.xml');

        $test = fwrite($fp, $list);
        if ($test) $mes= 'Данные в файл успешно занесены.';
        else $mes=  'Ошибка при записи в файл.';
        fclose($fp); //Закрытие файла
        return $mes;
    }

}