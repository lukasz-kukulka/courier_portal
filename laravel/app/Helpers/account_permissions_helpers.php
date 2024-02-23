<?php

use App\Http\Controllers\JsonParserController;

function __CHECK_ACCESS_FOR_ELEMENTS( $fieldName, $userAccountType, $fileType ) {
    $json = new JsonParserController;
    $premiumSettings = $json->premiumAction()[ $fileType . '_' . $fieldName ];
    $elementPermissions = null;

    if ( $fileType == 'courier_announcement' ) {
        $elementPermissions = $json->courierAnnouncementAction();
    }

    if( in_array( $userAccountType, $premiumSettings[ 'premium' ] ) ) {
        // dd( $elementPermissions[ 'premium' . '_' . $fieldName ] );
        return $elementPermissions[ 'premium' . '_' . $fieldName ];
    } else if( in_array( $userAccountType, $premiumSettings[ 'standard' ] ) ) {
        // dd( $elementPermissions[ 'standard' . '_' . $fieldName ] );
        return $elementPermissions[ 'standard' . '_' . $fieldName ];
    }
}