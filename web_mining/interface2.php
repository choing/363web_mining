<?php
    require_once("buildindex.php");
    require_once("stemming.php");


    if(isset($_POST['go'])) {
        $stemmer = new Stemmer;
        $s = $_POST['search'];
        //this stem will return an array of terms
        $s = $stemmer->stem_list($s);

        //if there is only a string
        if( count($s) == 1 ) {
            one_term_tweet($hasharr,$s[0]);
        } else {
            //ans will be an array with docid => di_value
            $ans = array();

            $parts = explode( ',' , $hasharr[$s[0]]);
            foreach( $parts as $part ) {
                $name = explode(' ' , $part);
                if( $name[0] == "" ) continue;
                $ans[$name[0]] = $name[1];
            }

            for( $i = 1 ; $i < count($s) ; $i++ ) {
                $ans = terms_tweet_intersect( $ans , $hasharr[$s[$i]] );
            }
            //$ans is an array containing all intersect id and the score of the document
            show_tweet($ans);

        }
    }

    //return tweets of the one term
    function one_term_tweet ($hash , $term) {
        if( isset($hash[$term]) ) {
            $ans = array();
            $parts = explode(',' , $hash[$term]);
            foreach ($parts as $part ) {
                $name = explode(' ',$part);

                if( $name[0] == "" ) continue;
                $ans[$name[0]] = $name[1];

            }

           show_tweet($ans);
        } else {
            echo "no such string " . $term . "</br>";
        }
    }

    //intersect with two or more terms
    function terms_tweet_intersect ( $term1_docid , $term2 ) {
        $ans = array();

        $parts = explode(',',$term2);
        foreach( $parts as $part ) {
            $name = explode(' ' , $part);
            if( $name[0] == "" ) continue;
            $term2_docid[$name[0]] = $name[1];
        }

        $temp_ans = array_intersect_key($term1_docid, $term2_docid);


        foreach($temp_ans as $id => $value) {
            $ans[$id] = $term1_docid[$id] + $term2_docid[$id];
            //echo $id . "</br>";
        }

        //all keys of $term1: all the docID that $term1 has
        return $ans;
    }

    //datatype of $listofmath is an array with id => score
    function show_tweet($listofmatch){

        $count = 0;
        foreach ($listofmatch as $id => $value ) {

            $rawpath = "./data/raw/";
            $line = file_get_contents( $rawpath . $id . '.txt');
            echo $line . "</br>";
            $count++;
        }
    }

?>
