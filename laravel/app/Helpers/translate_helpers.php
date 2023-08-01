<?php


function __FF( $translate_file, $word, $quantity = 1, $word_case = 'nominative', $is_capital = false ) {
    $capital = '';
    if ( $is_capital === true ) {
        $capital = '_capital';
    }
    $singular = '_plural';
    if ( $quantity == 1 ) {
        $singular = '_singular';
    }
    return __( $translate_file . '.' . $word . "_" . $word_case . $singular . $capital );
}
//nominative, genitive, dative, accusative, instrumental, locative, vocative

