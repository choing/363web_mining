<?php
/**
 * Created by JetBrains PhpStorm.
 * User: choing
 * Date: 15/05/2014
 * Time: 22:11
 * To change this template use File | Settings | File Templates.
 */

$files = glob("./data/index/*"); // get all file names
foreach($files as $file){ // iterate files
    if(is_file($file))
        unlink($file); // delete file
}

$files = glob("./data/raw/*"); // get all file names
foreach($files as $file){ // iterate files
    if(is_file($file))
        unlink($file); // delete file
}

?>