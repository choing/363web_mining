<link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">

<?php
    require_once("buildindex.php");
    require_once("stemming.php");
    ("./bootstrap/css/bootstrap.min.css");

    if(isset($_POST['go']) && !isset($_POST['temporal'])) {
        $stemmer = new Stemmer;
        $s = $_POST['search'];
        //this stem will return an array of terms
        $s = $stemmer->stem_list($s);

        // if the string is empty
        if ( empty($_POST['search']) ) {
            echo "Please input your string" ;
            exit(0);
        }

        // in buildingindex.php $hasharr[$term][$doc] = $value;
        //if there is only a string
        if( count($s) == 1 ) {
            $ans = one_term_tweet($hasharr,$s[0]);
            $doc_score = scoring( $hasharr,$s,$ans );
            $ans = sortScore($doc_score);
            show_tweet($ans);
        } else {
            //ans will be an array with docid
            $ans = array();

            // push all documents ID in term1 into the ans_arry first
            foreach( $hasharr[$s[0]] as $id => $value ) {
                array_push($ans,$id);
            }

            if ( strpos($_POST['search']," OR ") != false || strpos($_POST['search']," or ") != false) {
                for( $i = 1 ; $i < count($s) ; $i++ ) {
                    $ans = terms_tweet_or( $ans , $hasharr[$s[$i]] );
                }
            } else {
                for( $i = 1 ; $i < count($s) ; $i++ ) {
                    $ans = terms_tweet_intersect( $ans , $hasharr[$s[$i]] );
                }
            }

            //$ans is an array containing all intersect id and the score of the document
           // var_dump($ans);
            $doc_score = scoring( $hasharr,$s,$ans );
            $ans = sortScore($doc_score);
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
                if ( strpos($_POST['search']," OR ") != false ) {
                    for( $i = 1 ; $i < count($s) ; $i++ ) {
                        $ans = terms_tweet_or( $ans , $hasharr[$s[$i]] );
                    }
                } else {
                    for( $i = 1 ; $i < count($s) ; $i++ ) {
                        $ans = terms_tweet_intersect( $ans , $hasharr[$s[$i]] );
                    }
                }

            }


            //get an array in which all documents ID of matched ones
            $keys = array_keys($ans);

            foreach ( $keys as $id ) {

                if( $ans[$id] < $datearr[$user_date] || $ans[$id] > $datearr[$user_date2] ) {
                    // remove that one from ans
                    unset($ans[$id]);
                }

            }

            $doc_score = scoring( $hasharr,$s,$ans );
            $ans = sortScore($doc_score);
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

            return $ans;
        } else {
            echo "no such string " . $term . "</br>";
        }
    }

    // this function do the "OR" operation
    function terms_tweet_or ( $term1 , $term2 ) {
        $tem2 = array();
        $ans = $term1;
        foreach( $term2 as $id => $value ) {
            array_push($tem2,$id);
        }

        // merge the array of term1 to that of $tem2
        $ans = array_merge($ans,$tem2);
        // remove the dulipcates in $ans array
        $ans = array_unique($ans);


        return $ans;
    }

    //intersect with two or more terms
    function terms_tweet_intersect ( $term1 , $term2 ) {
        $tem2 = array();

        //this assign only want to emphasize the meaning of $term1
        $ans = $term1;
        foreach( $term2 as $id => $value ) {
            array_push($tem2,$id);
        }

        $ans = array_intersect($ans,$tem2);

        //all keys of $term1: all the docID that $term1 has
        return $ans;
    }

    function scoring( $indexarr , $query , $listofmatch ) {
        // $doc_score : suppose to be $id => score
        $doc_score = array();

        // $hasharr is the array of the index : $hasharr[$term][$doc] = $value;
        // $listofmath contain doc id of the matched documents
        foreach ( $query as $term ) {
            foreach ( $listofmatch as $id ) {
                //echo "hihihihi    " . $term . " has the document " . $id . " of " . $indexarr[$term][$id]  .  "</br>";
                if( isset($doc_score[$id]) ) {
                    if( isset($indexarr[$term][$id]) ) $doc_score[$id] += $indexarr[$term][$id];
                } else {
                    if( isset($indexarr[$term][$id]) ) $doc_score[$id] = $indexarr[$term][$id];
                }
            }
        }

        return $doc_score;

    }

    //This function sort the associative by value
    function sortScore($listofmatch) {
        //The structure of $listofmatch will be an associative array : id => score
        // $ans is an array which only
        $ans = array();

        //echo "<h3> Before sorting </h3>";
        /*foreach($listofmatch as $id => $score ) {
            echo $id . " has the score of " . $score . "</br>" ;
        }*/

        uasort($listofmatch, 'cmp');

        //echo "<h3> After sorting </h3>";
        /*foreach($listofmatch as $id => $score ) {
            echo $id . " has the score of " . $score . "</br>" ;
        }*/
        foreach( $listofmatch as $id => $score ) {
            array_push($ans,$id);
        }

        return $ans;
    }

    function cmp($a, $b) {
        if ($a == $b) {
           return 0;
        }
        return ($a < $b) ? 1 : -1;
    }

    //datatype of $listofmath is an array with id => score
    function show_tweet( $listofmatch ){

        $count = 0;
        //echo" <span class='label label-default'>hihi</span>";
        foreach ($listofmatch as $id ) {

            $rawpath = "./data/raw/";
            $line = file_get_contents( $rawpath . $id . '.txt');
           // echo $line . "</br>";
            list( $date , $user , $content ) = explode(';',$line);
            echo "<h4>".$date . "</h4>";
            echo "<h4>" . $user . '  ' . $content . "</h4></br>";
            $count++;
        }
    }

?>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>

<script src="./bootstrap/js/bootstrap.min.js"></script>