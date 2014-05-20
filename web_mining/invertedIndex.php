<?php
    /*  1, tokenize
     *  2, stemming the terms
     *  3, count tf, wf, di normalized wf
     *  4, push them into an associative array
     */

    /*
        Treat a tweet as a document.
        Explanation for the if/else case:
        Some tweets may have the format like:

        2014-03-26 22:00:27; @choisoo_pop; RT @TUTOR_TOM: A#@#4H!@4!58,2%#L!+2'4"2%1"!5A0C
        I
        7H-'H2 University of Siam #HBDCU

        They should be treated as a tweet. Thus, if the current line does not contain a ';'
        they should be part of the former line
    */
    ini_set('memory_limit', '5120M');
    require_once("stemming.php");

    //array_push for stop words
    $stops = fopen('./data/stopwords.txt','r');
    $stopsarr = array();
    while( !feof($stops) ) {
        $current = fgets($stops);
        $current = trim(preg_replace('/[^A-Za-z0-9\-]/', '', $current));
        array_push($stopsarr , $current);
    }

    //$tweets = fopen('/Users/choing/Desktop/context.txt','r');

    $tweets = fopen('./data/context.txt','r');
    $stemmer = new Stemmer;
    $docID = -1;
    $terms_di = array();

    while(!feof($tweets)) {
        $termsArray = array();          //suppose it is an associative array , ( tf , wf , dwf ) , arraytut.php show the idea
        $wfsqu = 0.00;                  // wf1^2 + wf2^2 + wf3^2 + ...... + wfn^2

        $line = fgets($tweets);

        if ( strpos($line,';') == 19 && is_numeric(substr($line,0,1)) ) {     //if the line contains a ';'
            $docID++;

            list( $date , $user , $content ) = array_pad( explode(';',$line) , 3 , "");

            //write the tweet to a text file
            $filepath = "./data/raw/$docID.txt";
            $file = fopen( $filepath , "w" );
            fwrite( $file, $line );
            fclose($file);

        } else {
            $content = $line;

            $file = fopen($filepath,"a");
            fwrite($file,$content);
            fclose($file);
        }

        //making the index
        $tokens = explode(' ',$content);

        //counting the tf
        foreach( $tokens as $token ) {
            //only do the index for meaningful ENGILSH word
            //remove all special characters
            $token = trim(preg_replace('/[^A-Za-z0-9]/', '', $token));

            // if the token is an empty string
            if( $token == '' ) {continue;}

            //if the token is one of the stopword , skip it
            if( array_search( $token , $stopsarr ) ) { continue; }

            //if the token is a hyperlink, skip it
            if( preg_match('*http*',$token) ) {continue;}

            //if the token has a length more than 20, skip it
            if( (strlen($token) > 20) ) {continue;}

            $token = $stemmer->Stem($token);
            if($token == "" ) {continue;}                   // skip the "" word

            if( isset($termsArray[$token]) ) {
                $termsArray[$token]['tf']++;
            } else {
                $termsArray[$token] = array();
                $termsArray[$token]['tf'] = 1;
            }

        }

        //count wf
        foreach( $termsArray as $term => $vararr ) {
            $temtf = $termsArray[$term]['tf'];
            $temwf = 1 + log10($temtf);
            $termsArray[$term]['wf'] = $temwf;
            $wfsqu += pow( $temwf , 2 ) ;
        }

        //count di
        foreach( $termsArray as $term => $vararr ) {
            $temwf = $termsArray[$term]['wf'];
            $temdi = $temwf / sqrt( $wfsqu );

            //putting into the array
            $terms_di[$term][$docID] = $temdi;
        }
    }


    writetofile( $terms_di );

//write to the index
    function writetofile( $terms ) {
        //var_dump($terms);

        $counter = 0;
        $filepath = "./data/index/" . $counter . ".txt";

        foreach( $terms as $term => $vararr ) {  // vararr : array ; key: docID ; value: Norm di.

            $fileopen = fopen($filepath,'a');
            fwrite($fileopen,$term . ":");

            foreach( $vararr as $doc => $Normdi ) {
                fwrite($fileopen, $doc . " " . $Normdi . ",");
            }

            fwrite($fileopen , "\n");
            $counter++;
            if($counter % 30000 == 0) {
                $filepath = "./data/index/" . $counter . ".txt";
                fclose($fileopen);
            }
        }
        fclose($fileopen);
    }

?>