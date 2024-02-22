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

    public function getMenuData($returnType = 'array') {
        return $this->getJsonData('top_menu.json', $returnType);
    }

    public function getMenuUserData($returnType = 'array') {
        return $this->getJsonData('top_menu_user.json', $returnType);
    }

    public function getCourierAnnouncementData($returnType = 'array') {
        return $this->getJsonData('courier_announcement.json', $returnType);
    }

    private function getJsonDataWithCountrySuffix($prefix, $countryCode, $returnType = 'array') {
        $filename = $prefix . '_' . $countryCode . '.json';
        return $this->getJsonData($filename, $returnType);
    }

    public function getPostCodesDataByCountry($countryCode, $returnType = 'array') {
        return $this->getJsonDataWithCountrySuffix('post_codes', $countryCode, $returnType);
    }

    public function getCourierAnnouncementAccessElements($returnType = 'array') {
        return $this->getJsonData('accounts_access_for_elements.json', $returnType);
    }
    
}

