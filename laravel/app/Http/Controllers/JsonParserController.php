<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JsonParserController extends Controller
{
    private $settingsDir;

    public function __construct() {
        $this->settingsDir = 'settings' . DIRECTORY_SEPARATOR;
    }

    public function getJsonData($filename, $returnType = 'array') {
        $filePath = resource_path($this->settingsDir . $filename);
        $jsonData = file_get_contents($filePath);

        switch ($returnType) {
            case 'object':
                return json_decode($jsonData);
            case 'string':
                return $jsonData;
            case 'array':
            default:
                return json_decode($jsonData, true);
        }
    }

    public function menuAction() {
        return $this->getJsonData('top_menu.json');
    }

    public function menuUserAction() {
        return $this->getJsonData('top_menu_user.json');
    }

    public function accountAction() {
        return $this->getJsonData('accounts.json');
    }

    public function directionsAction() {
        return $this->getJsonData('directions.json');
    }

    public function cargoAction() {
        return $this->getJsonData('cargo.json');
    }

    public function searchAnnouncementAction() {
        return $this->getJsonData('announcement.json');
    }

    public function getPostCodes($direction) {
        $variableName = 'post_codes_' . $direction . '.json';
        return $this->getJsonData($variableName);
    }

    public function plPostCodeAction() {
        return $this->getJsonData('post_codes_pl.json');
    }

    public function ukPostCodeAction() {
        return $this->getJsonData('post_codes_uk.json');
    }

    public function courierAnnouncementAction() {
        return $this->getJsonData('courier_announcement.json');
    }

    public function courierAnnouncementAccessElementsAction() {
        return $this->getJsonData('accounts_access_for_elements.json');
    }
}
