<?php
/**
 * Created by PhpStorm.
 * User: myindexlike
 * Date: 09.03.2016
 * Time: 3:29
 */
if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}

require_once "sitemap.php";

$map = new sitemap(5,'sitemap');

echo $map->creatingXML();


/**
 * формирование итогового файла cо списокм остальных
 *
 * @param $file_ar
 *
 * @return bool|string
 */
/*
function creationGeneralizingXML ($file_ar){
    global $config;
    if (count($file_ar)>0){
        $list='';
        foreach ($file_ar as $key => $value) {
            if ($value!='index.html' and $value!='all.xml'){
                $list.='<sitemap><loc>' . $config['files_xml_dir_url'] . $value . '</loc></sitemap>';
            }
        }

        $str ='<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.$list.'</sitemapindex>';
        return saveFile ($str,'all');
    }
    else {
        return FALSE;
    }
}
*/

/**
 * получаем список файлов из папки импорта
 * @return string
 */
/*
function get_file_from_folder () {
    global $config;
    $st=array();
    $out='';
    if ($handle = opendir($config['files_xml_dir'])) {
        $file_ar = array();
        $i=0;
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != ".." && $file != "index.html") {
                // echo 'opendir='.$config['files_import_dir'].'<br />';
                //  echo "Файл $file в последний раз был изменен: " . date("F d Y H:i:s.", filectime($config['files_import_dir'].$file)).'<br />';
                $date = date("F d Y H:i:s.", filectime($config['files_xml_dir'].$file));
                $i++;
                $date_U = date("U", filectime($config['files_xml_dir'].$file))+$i;

                $st[$date_U]= '<tr><td><a target="_blank" href="' .$config['files_xml_dir_url'].$file.'">' .$config['files_xml_dir_url'].$file.'</a></td><td>'.$date.'</td></tr>';

                $file_ar[]=$file;
            }
        }
        if (count($st)>0){
            creationGeneralizingXML($file_ar);
            krsort($st);
            $out='<table class="table table-striped">' . implode("", $st) . '</table>';
            closedir($handle);
        }
    }
    return $out;
}
*/





