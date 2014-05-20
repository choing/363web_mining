<?php
    require_once("buildindex.php");
    require_once("stemming.php");

    if(isset($_POST['go']) && !isset($_POST['temporal'])) {
        $stemmer = new Stemmer;
        $s = $_POST['search'];
        //this stem will return an array of terms
        $s = $stemmer->stem_list($s);

        //if there is only a string
        if( count($s) == 1 ) {
            $ans = one_term_tweet($hasharr,$s[0]);
            show_tweet($ans);
        } else {
            //ans will be an array with docid => di_value
            $ans = array();

            foreach( $hasharr[$s[0]] as $id => $value ) {
                array_push($ans,$id);
            }

            //var_dump($ans);
            for( $i = 1 ; $i < count($s) ; $i++ ) {
                $ans = terms_tweet_intersect( $ans , $hasharr[$s[$i]] );
            }
            //$ans is an array containing all intersect id and the score of the document
           // var_dump($ans);
            show_tweet($ans);
        }
    } else if(isset($_POST['go']) && isset($_POST['temporal'])) {  // this is the case of temporal search

	    $day = $_POST['day'];
		$month = $_POST['month'];
		$year = $_POST['year'];

		$day2 = $_POST['day1'];
		$month2 = $_POST['month1'];
		$year2 = $_POST['year1'];


        if ( strlen($month) < 2 ) {
            $user_date = $year . "-0" . $month;
        } else {
            $user_date = $year . "-" . $month;
        }

        if ( strlen($day) < 2 ) {
            $user_date .= "-0" . $day;
        } else {
            $user_date .= "-" . $day;
        }

        if ( strlen($month2) < 2 ) {
            $user_date2 = $year2 . "-0" . $month2;
        } else {
            $user_date2 = $year2 . "-" . $month2;
        }

        if ( strlen($day2) < 2 ) {
            $user_date2 .= "-0" . $day2;
        } else {
            $user_date2 .= "-" . $day2;
        }
        /*
         * This part has logical error.. 2014-01-01 will become 2014-1-01 , because the if statement in dat will overwrite the month. "-"
		/*
        if($month < 10 && $month > 0)
		$user_date = $year . "-0" . $month . "-" . $day;
		if($day < 10 && $day > 0)
		$user_date = $year . "-" . $month . "-0" . $day;

		$user_date2 = $year2 . "-" . $month2 . "-" . $day2;

		if($month2 < 10 && $month2 > 0)
		$user_date2 = $year2 . "-0" . $month2 . "-" . $day2;
		if($day2 < 10 && $day2 > 0)
		$user_date2 = $year2 . "-" . $month2 . "-0" . $day2;*/

		// I have to use this associative array: $days[$date[0]] = $docID;   2014-03-26
        // $datearr is frmo buildindex.php
		if ( isset($datearr[$user_date]) && isset($datearr[$user_date2]) ) {
			$stemmer = new Stemmer;
            $s = $_POST['search'];
            //this stem will return an array of terms
            $s = $stemmer->stem_list($s);

            if ( count($s) == 1 ) {
                $ans = one_term_tweet($hasharr,$s[0]);
            } else {

                //ans will be an array with docid => di_value
                $ans = array();

                foreach( $hasharr[$s[0]] as $id => $value ) {
                    array_push($ans,$id);
                }

                //var_dump($ans);
                for( $i = 1 ; $i < count($s) ; $i++ ) {
                    $ans = terms_tweet_intersect( $ans , $hasharr[$s[$i]] );
                }

            }

            //var_dump($ans);
            $keys = array_keys($ans);
            foreach ( $keys as $id ) {

                if( $ans[$id] < $datearr[$user_date] || $ans[$id] > $datearr[$user_date2] ) {
                    // remove that one from ans
                    unset($ans[$id]);
                }

            }

            show_tweet($ans);
		} else{
			echo "no results in such date\n";
		}

	}

    //return tweets of the one term
    function one_term_tweet ($hash , $term) {
        if ( isset($hash[$term]) ) {
            $ans = array();

            foreach ( $hash[$term] as $doc => $value ) {
                array_push($ans,$doc);
            }
            //var_dump($hash[$term]);

            return $ans;
        } else {
            echo "no such string " . $term . "</br>";
        }
    }

    //intersect with two or more terms
    function terms_tweet_intersect ( $term1 , $term2 ) {
        $tem2 = array();
        $ans = $term1;
        foreach( $term2 as $id => $value ) {
            array_push($tem2,$id);
        }

        $ans = array_intersect($ans,$tem2);

        //all keys of $term1: all the docID that $term1 has
        return $ans;
    }


    //datatype of $listofmath is an array with id => score
    function show_tweet($listofmatch){

        //var_dump($listofmatch);
        $count = 0;
        foreach ($listofmatch as $id ) {

            $rawpath = "./data/raw/";
            $line = file_get_contents( $rawpath . $id . '.txt');
            echo $line . "</br>";
            $count++;
        }
    }

?>
