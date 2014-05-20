<?php
/*
 *   This file gets  the index file ,
 *   $hasharr is the inverted index for terms
 *   $datearr is the time index
 */


    $hasharr = array();
    //$hasharr: $term => $string
    //the string is a long string: docID $score, docID $score,docID $score,docID $score,docID $score,docID $score,.....

    foreach( glob('./data/index/*.*') as $filepath ) {
        $file = file($filepath);

        //var_dump($file);
        foreach ( $file as $line ) {
            //$doc => docID $score, docID $score,docID $score,docID $score,docID $score,docID $score,......
            list( $term , $doc_value ) = explode(':',$line);


            $doc_value = trim(preg_replace('/\s\s+/', '', $doc_value));
            //$hasharr[$term] = trim(preg_replace('/\s\s+/', '', $doc));

            $doc_value = explode(',' , $doc_value );
            foreach ( $doc_value as $doc_value1 ) {
                if ( $doc_value1 == "" ) continue;
                list( $doc , $value ) = explode(' ',$doc_value1);
                $hasharr[$term][$doc] = $value;
            }

        }

    }

    //var_dump($hasharr);

    $datearr = array();
    $filepath = "./data/date_index/date_index.txt";
    $file = file($filepath);
    foreach( $file as $line ) {
        list( $date , $doc ) = explode(' ' , $line);
        $datearr[$date] = trim(preg_replace('/\s\s+/', '', $doc));
    }


?>