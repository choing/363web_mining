<?php
    require_once("buildindex.php");
    require_once("stemming.php");


    if(isset($_POST['go'])) {
        //$context = explode(' ' , $_POST['search']);
        $stemmer = new Stemmer;
        $s1 = $_POST['search'];
        $s1 = $stemmer->stem($s1);
        //echo $s1 . "</br>";
        return_tweet($hasharr, $s1);
        //echo $hasharr[$s1] . "</br>";
    }

    function return_tweet ($hash , $term) {
        $rawpath = "./data/raw/";
        if( isset($hash[$term]) ) {
            $parts = explode(',' , $hash[$term]);
            foreach ($parts as $part ) {
                $name = explode(' ',$part);
                if( $name[0] == "" ) continue;
                $line = file_get_contents( $rawpath . $name[0] . '.txt');
                echo $line . "</br>";
            }
        }
    }

?>
