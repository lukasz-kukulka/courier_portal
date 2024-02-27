<?php

use App\Http\Controllers\JsonParserController;

function __CHECK_ACCESS_FOR_ELEMENTS( $fieldName, $userAccountType, $fileType ) {
    $json = new JsonParserController;
    $premiumSettings = $json->premiumAction()[ $fileType . '_' . $fieldName ];
    $elementPermissions = null;

    $elementPermissions = $json->getJsonData( $fileType );

    if( in_array( $userAccountType, $premiumSettings[ 'premium' ] ) ) {
        return $elementPermissions[ 'premium' . '_' . $fieldName ];
    } else if( in_array( $userAccountType, $premiumSettings[ 'standard' ] ) ) {
        return $elementPermissions[ 'standard' . '_' . $fieldName ];
    }
}