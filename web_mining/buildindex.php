<?php

    $hasharr = array();
    //$hasharr: $term => $string
    //the string is a long string: docID $score, docID $score,docID $score,docID $score,docID $score,docID $score,.....

    foreach( glob('./data/index/*.*') as $filepath ) {
        $file = file($filepath);

        //var_dump($file);
        foreach ( $file as $line) {
            //$doc => docID $score, docID $score,docID $score,docID $score,docID $score,docID $score,......
            list( $term , $doc ) = explode(':',$line);

            $hasharr[$term] = trim(preg_replace('/\s\s+/', '', $doc));
        }

    }

?>