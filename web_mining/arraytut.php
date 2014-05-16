<?php
    $termsarray = array();
    $var1 = 'hi';
    $var2 = 'bye';
    $var3 = 'yeah';

    $termsarray[$var1] = array();
    $termsarray[$var2] = array();
    $termsarray[$var3] = array();

    $termsarray[$var1]['tf'] = 10;
    $termsarray[$var1]['wf'] = 1 + log10(10);
    $termsarray[$var1]['dwf'] = 'skip computation' ;

    $termsarray[$var2]['tf'] = 20;
    $termsarray[$var2]['wf'] = 1 + log10(20);
    $termsarray[$var2]['dwf'] = 'skip computation' ;

    $termsarray[$var3]['tf'] = 30;
    $termsarray[$var3]['wf'] = 1 + log10(30);
    $termsarray[$var3]['dwf'] = 'skip computation' ;

    foreach( $termsarray as $term) {
        echo $term['tf'] . "\n";
    }

?>