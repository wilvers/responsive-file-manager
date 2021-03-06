<?php
include 'vendor/autoload.php';

//if ($_SESSION['RF']["verify"] != "RESPONSIVEfilemanager") {
//    die('forbiden');
//}

error_reporting(E_ALL);
ini_set("display_errors", 1);


$values = include 'src/wilvers/FileManager/Config/config.php';
$config = new Wilvers\FileManager\Config\ArrayConfig($values);
//var_dump($config->get('viewerjs_file_exts'));
$i18n = Wilvers\FileManager\Ressource\i18n\Translation::get('fr_FR');
//var_dump($i18n->get('Upload_file'));

$fm = new Wilvers\FileManager\FileManager();
$fm
        ->setConfig($config)
        ->setTranslation($i18n)
;

//
$debugbar = new Wilvers\PhpDebugBar\PhpDebugBar();
$st = new Wilvers\PhpDebugBar\Storage\CustomFileStorage('/logs/test_dev/');
$st->setCollectorsToSave(array('Users', 'Ip', 'exceptions', 'memory', 'request', 'messages', '__meta'));
$debugbar->setStorage($st);
$debugbar->addCollector(new Wilvers\PhpDebugBar\DataCollector\GenericCollector('debug1'));

$debugbarRenderer = $debugbar
        ->getJavascriptRenderer()
        ->setEnableJqueryNoConflict(false);
$debugbarRenderer->setOpenHandlerUrl('open.php');
$debugbar['messages']->addMessage('hello from redirect');
$dbHead = $debugbarRenderer->renderHead();
$dbHtml = $debugbarRenderer->render();
$renderParams = array($dbHead, $dbHtml);
$search = array('<!--[[head-replace]]-->', '<!--[[body-bottom-replace]]-->');
?>

<!--
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <div>

        </div>
        <iframe src="filemanager/filemanager/dialog.php" height="800" width="1024">
    </body>
</html>
-->
<!--[[head-replace]]--><!--[[body-bottom-replace]]-->
<?php
$html = $fm->render($renderParams);
if (preg_match("/<!--[[head-replace]]-->/", $html))
    echo preg_replace("<!--[[head-replace]]-->", $dbHead, $html);
//echo str_replace($search, $renderParams, $fm->render($renderParams));
