<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JsonParserController extends Controller
{
    public function __construct() {
        $settingsDir = 'settings'. DIRECTORY_SEPARATOR;
        $this->menu_json_file  = resource_path( $settingsDir . 'top_menu.json');
        $this->account_json_file  = resource_path( $settingsDir . 'accounts.json');
        $this->directions_json_file  = resource_path( $settingsDir . 'directions.json');
        $this->cargo_json_file  = resource_path( $settingsDir . 'cargo.json');
        $this->search_announcement_json_file  = resource_path( $settingsDir . 'announcement.json');
        $this->post_codes_pl  = resource_path( $settingsDir . 'post_codes_pl.json');
        $this->post_codes_uk  = resource_path( $settingsDir . 'post_codes_uk.json');
        $this->courier_announcement_json_file = resource_path( $settingsDir . 'courier_announcement.json');
        $this->element_access = resource_path( $settingsDir . 'accounts_access_for_elements.json');
    }

    public function menuAction() {
        $jsonData = file_get_contents( $this->menu_json_file );
        $json = json_decode( $jsonData, true );
        return $json;
    }

    public function accountAction() {
        $jsonData = file_get_contents( $this->account_json_file );
        $json = json_decode( $jsonData, true );
        return $json;
    }

    public function directionsAction() {
        $jsonData = file_get_contents( $this->directions_json_file );
        $json = json_decode( $jsonData, true );
        return $json;
    }

    public function cargoAction() {
        $jsonData = file_get_contents( $this->cargo_json_file );
        $json = json_decode( $jsonData, true );
        return $json;
    }

    public function searchAnnouncementAction() {
        $jsonData = file_get_contents( $this->search_announcement_json_file );
        $json = json_decode( $jsonData, true );
        return $json;
    }

    public function plPostCodeAction() {
        $jsonData = file_get_contents( $this->post_codes_pl );
        $json = json_decode( $jsonData, true );
        return $json;
    }

    public function ukPostCodeAction() {
        $jsonData = file_get_contents( $this->post_codes_uk );
        $json = json_decode( $jsonData, true );
        return $json;
    }

    public function courierAnnouncementAction() {
        $jsonData = file_get_contents( $this->courier_announcement_json_file );
        $json = json_decode( $jsonData, true );
        return $json;
    }

    public function courierAnnouncementAccessElementsAction() {
        $jsonData = file_get_contents( $this->element_access );
        $json = json_decode( $jsonData, true );
        return $json;
    }

    private $menu_json_file;
    private $account_json_file;
    private $directions_json_file;
    private $cargo_json_file;
    private $search_announcement_json_file;
    private $post_codes_pl;
    private $post_codes_uk;
    private $courier_announcement_json_file;
    private $element_access;
}
