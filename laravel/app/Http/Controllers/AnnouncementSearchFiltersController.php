<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class AnnouncementSearchFiltersController extends Controller {

    public function getFiltersData( $request ) {

        $json = new JsonParserController;
        $filtersDataArray = [];
        $directionFrom = $request->input( 'post_codes_from_filter_body_direction' );
        $directionTo = $request->input( 'post_codes_to_filter_body_direction' );
        $postCodesFrom = [];
        $postCodesTo = [];
        foreach ( $request->all() as $key => $value ) {
            $searchFieldPostCodesFrom = 'name_post_code_from_checkbox_' . $directionFrom . '_';
            $searchFieldPostCodesTo = 'name_post_code_to_checkbox_' . $directionTo . '_';

            if ( !$request->has('name_all_dates_checkbox') ) {
                //dd( $request->input( 'name_date_field' ), $request->input( 'name_days_after' ), $request );
                $filtersDataArray[ 'start_date' ] = Carbon::createFromFormat( 'Y-m-d', $request->input( 'name_date_field' ) )->subDays( (int)$request->input( 'name_days_before' ) )->toDateString();
                $filtersDataArray[ 'end_date' ] = Carbon::createFromFormat( 'Y-m-d', $request->input( 'name_date_field' ) )->addDays( (int)$request->input( 'name_days_after' ) )->toDateString();
            }

            if ( strpos( $key, $searchFieldPostCodesFrom ) === 0 ) {
                $postCodesFrom[ str_replace( $searchFieldPostCodesFrom, '', $key ) ] = 1;
            }

            if ( strpos( $key, $searchFieldPostCodesTo) === 0 ) {
                $postCodesTo[ str_replace( $searchFieldPostCodesTo, '', $key ) ] = 1;
            }
        }
        if ( $directionFrom != null ) {
            $filtersDataArray[ 'direction_from' ] = $directionFrom;
        }

        if ( $directionTo != null ) {
            $filtersDataArray[ 'direction_to' ] = $directionTo;
        }

        if ( count( $postCodesFrom ) > 0 ) {
            $filtersDataArray[ 'post_codes_from' ] = $postCodesFrom;
        }

        if ( count( $postCodesTo ) > 0 ) {
            $filtersDataArray[ 'post_codes_to' ] = $postCodesTo;
        }

        foreach( $json->cargoAction()[ 'cargo_types' ] as $type ) {
            $filedName = 'name_' . $type[ 'id' ] . '_type_checkbox';
            if ( $request->has( $filedName ) ) {
                $filtersDataArray[ $filedName ] = 1;
            }
        }

        return $filtersDataArray;
    }

    public function getCourierAnnouncementAfterFiltered( $query, $filtersData ) {
        $filteredAnnouncements = $query;
        $json = new JsonParserController;
        $allDirections = $json->directionsAction();
        $filteredAnnouncements = $this->getCourierDirectionFromFilteredData( $filteredAnnouncements, $filtersData, $allDirections );
        $filteredAnnouncements = $this->getCourierDirectionToFilteredData( $filteredAnnouncements, $filtersData, $allDirections );
        $filteredAnnouncements = $this->getCourierDateFilteredData( $filteredAnnouncements, $filtersData );
        $filteredAnnouncements = $this->getPersonalAnnouncementFilteredData( $filteredAnnouncements, $filtersData );

        return $filteredAnnouncements;
    }

    public function getUserAnnouncementAfterFiltered( $query, $filtersData ) {
        $filteredAnnouncements = $query;
        $json = new JsonParserController;
        // $allDirections = $json->directionsAction();
        //dd( $filteredAnnouncements );
        $filteredAnnouncements = $this->getDirectionsAndPostCodesUserFilteredData( $filteredAnnouncements, $filtersData );
        $filteredAnnouncements = $this->getUserDateFilteredData( $filteredAnnouncements, $filtersData );
        $filteredAnnouncements = $this->getCargoTypesFilteredData( $filteredAnnouncements, $filtersData, $json->cargoAction()[ 'cargo_types' ] );

        return $filteredAnnouncements;
    }

    private function getCourierDirectionFromFilteredData( $query, $filtersData, $allDirections ) {
        if ( array_key_exists( 'direction_from', $filtersData ) ) {
            $query->whereHas('dateAnnouncement', function ( $query ) use ( $filtersData ) {
                $query->where( 'dir_from', $filtersData[ 'direction_from' ] );
            });
            if ( array_key_exists('post_codes_from', $filtersData ) ) {
                foreach ( $filtersData[ 'post_codes_from' ] as $key => $value ) {
                    $query->whereHas('postCodes' . $allDirections[ $filtersData[ 'direction_from' ] ][ 'request_name' ] . 'Announcement', function ( $query ) use ( $key, $value ) {
                        $query->where( $key, $value );
                    });
                }
            }
        }

        return $query;
    }

    private function getCourierDirectionToFilteredData( $query, $filtersData, $allDirections ) {
        if ( array_key_exists( 'direction_to', $filtersData ) ) {
            $query->whereHas('dateAnnouncement', function ( $query ) use ( $filtersData ) {
                $query->where( 'dir_to', $filtersData[ 'direction_to' ] );
            });

            if ( array_key_exists( 'post_codes_to', $filtersData ) ) {
                foreach ( $filtersData[ 'post_codes_to' ] as $key => $value ) {
                    $query->whereHas('postCodes' . $allDirections[ $filtersData[ 'direction_to' ] ][ 'request_name' ] . 'Announcement', function ( $query ) use ( $key, $value ) {
                        $query->where( $key, $value );
                    });
                }
            }
        }

        return $query;
    }

    private function getCourierDateFilteredData( $query, $filtersData ) {
        if ( array_key_exists( 'start_date', $filtersData) || array_key_exists('end_date', $filtersData ) ) {
            $query->whereHas('dateAnnouncement', function ( $query ) use ( $filtersData ) {
                $query->where( 'date', '>=', $filtersData[ 'start_date' ] )
                      ->where( 'date', '<=', $filtersData[ 'end_date' ] );
            });
        }

        return $query;
    }

    private function getUserDateFilteredData( $query, $filtersData ) {
        if ( array_key_exists( 'start_date', $filtersData) || array_key_exists('end_date', $filtersData ) ) {
            $query->where( 'expect_sending_date', '>=', $filtersData[ 'start_date' ] )
                  ->where( 'expect_sending_date', '<=', $filtersData[ 'end_date' ] );

        }

        return $query;
    }

    private function getDirectionsAndPostCodesUserFilteredData( $query, $filtersData ) {
        if ( array_key_exists( 'direction_from', $filtersData ) ) {
            $query->where( 'direction_sending', $filtersData[ 'direction_from' ] );
        }

        if ( array_key_exists( 'post_codes_from', $filtersData ) ) {
            $query->where(function ($query) use ( $filtersData ) {
                foreach ( $filtersData[ 'post_codes_from' ] as $key => $value) {
                    $query->orWhere('post_code_prefix_sending', $key);
                }
            });
        }

        if ( array_key_exists( 'direction_to', $filtersData ) ) {
            $query->where( 'direction_receiving', $filtersData[ 'direction_to' ] );
        }

        if ( array_key_exists( 'post_codes_to', $filtersData ) ) {
            $query->where(function ($query) use ( $filtersData ) {
                foreach ( $filtersData[ 'post_codes_to' ] as $key => $value ) {
                    $query->orWhere('post_code_prefix_receiving', $key);
                }
            });
        }
        return $query;
    }

    private function getPersonalAnnouncementFilteredData( $query, $filtersData ) {
        if ( array_key_exists( 'direction_from', $filtersData ) ) {
            $query->where( 'author', $filtersData[ 'user_announcements' ] );
        }

        return $query;
    }

    private function getCargoTypesFilteredData( $query, $filtersData, $allCargoTypes ) {
        //dd( $query->get(), $filtersData, $allCargoTypes );
        // $dontExist = [];
        foreach( $allCargoTypes  as $type ) {
            $filedName = 'name_' . $type[ 'id' ] . '_type_checkbox';
            if ( array_key_exists( $filedName, $filtersData ) ) {
                $query->orWhereHas( $type[ 'id' ] . 'Announcement' );
                // $query->has( $type[ 'id' ] . 'Announcement' );
            }
        }
        // dd( $dontExist );
        return $query;
    }

}