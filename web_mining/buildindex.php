<?php

    $hasharr = array();

    foreach( glob('./data/index/*.*') as $filepath ) {
        $file = file($filepath);

        //var_dump($file);
        foreach ( $file as $line) {
            list( $term , $doc ) = explode(':',$line);

            $hasharr[$term] = trim(preg_replace('/\s\s+/', '', $doc));
        }
        //var_dump($hasharr);
        //echo $term . " ";
    }

?>