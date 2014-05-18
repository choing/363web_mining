<?php

	$tweets = fopen('/Users/choing/Desktop/context.txt','r');
	$docID = 0;
    $days = array();

	while(!feof($tweets)) {
	    $line = fgets($tweets);

        if ( strpos($line,';') == 19 && is_numeric(substr($line,0,1)) ) {
		    list( $date , $user , $content ) = array_pad( explode(';',$line) , 3 , "");
		    $date = explode(' ', $date);

            // isset(): Returns TRUE if var exists and has value other than NULL, FALSE otherwise.
            // !isset => false(false) => true
            if( !isset( $days[$date[0]] ) ) {
                $days[$date[0]] = $docID;
            }

            $docID++;
        }
	}

    $filepath = "./data/date_index/date_index.txt";
    $fileopen = fopen($filepath,"a");
    foreach ( $days as $date => $doc ) {
        $details =  $date . " " . $doc;
        fwrite( $fileopen , $details );
        fwrite( $fileopen , "\n" );

    }
    fclose( $fileopen );


?>
