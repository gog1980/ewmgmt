<?php

/*
  Copyright 2017 Oscar.

  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at

  http://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License.
 */

namespace LAMgmt\Util;

use DateTime;
use Exception;
use LAMgmt\Exception\NotFoundException;

/**
 * Miscellaneous utility methods.
 */
final class Utils {

    private function __construct() {
        
    }

    /**
     * Generate link.
     * @param string $page target page
     * @param array $params page parameters
     */
    public static function createLink($page, array $params = []) {
        unset($params['page']);
        return 'index.php?' . http_build_query(array_merge(['page' => $page], $params));
    }

    /**
     * Generate menu full menum <li> item
     * @param string $label link text
     * @param string $targetPage target page
     * @param string $icon item icon
     */
    public static function getMenuItem($label, $targetPage, $icon, $id = "") {
        $ret = "<li";
        if (($GLOBALS['currPage'] === $targetPage) && (self::isURLOk($targetPage))) {
            $ret .= " class='active'";
        }
        ($id == "") ? $ret .= " id='m-".$targetPage."'" : $ret .= " id='m-".$id."'";
        $ret .= "><a href='" . self::createLink($targetPage) . "'><i class='fa $icon'></i> $label</a></li>";
        return $ret;
    }
    
    public static function isURLOk($targetPage){
        switch ($targetPage) {
            case "alumnesEdit":
                return (!(array_key_exists('id', $_GET)));
                break;
            default:
                return true;
                break;
        }
    }

    /**
     * Format date.
     * @param DateTime $date date to be formatted
     * @return string formatted date
     */
    public static function formatDate(DateTime $date = null) {
        if ($date === null) {
            return '';
        }
        return $date->format('m/d/Y');
    }

    /**
     * Format date and time.
     * @param DateTime $date date to be formatted
     * @return string formatted date and time
     */
    public static function formatDateTime(DateTime $date = null) {
        if ($date === null) {
            return '';
        }
        return $date->format('m/d/Y H:i');
    }

    /**
     * Redirect to the given page.
     * @param type $page target page
     * @param array $params page parameters
     */
    public static function redirect($page, array $params = []) {
        header('Location: ' . self::createLink($page, $params));
        die();
    }

    /**
     * Get value of the URL param.
     * @return string parameter value
     * @throws NotFoundException if the param is not found in the URL
     */
    public static function getUrlParam($name) {
        if (!array_key_exists($name, $_GET)) {
            throw new NotFoundException('URL parameter "' . $name . '" not found.');
        }
        return $_GET[$name];
    }

    /**
     * Capitalize the first letter of the given string
     * @param string $string string to be capitalized
     * @return string capitalized string
     */
    public static function capitalize($string) {
        return ucfirst(mb_strtolower($string));
    }

    /**
     * Escape the given string
     * @param string $string string to be escaped
     * @return string escaped string
     */
    public static function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES);
    }

    /**
     * 
     */
    public static function getCurrentPageStyle() {
        $ret = "";
        switch ($GLOBALS['currPage']) {
            case "alumnes":
                $ret = "<link href=\"plugins/dataTables/dataTables/css/dataTables.bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\">";
                $ret .= "<link href=\"plugins/dataTables/buttons/css/buttons.dataTables.min.css\" rel=\"stylesheet\" type=\"text/css\">";
                $ret .= "<link href=\"plugins/dataTables/select/css/select.dataTables.min.css\" rel=\"stylesheet\" type=\"text/css\">";
                break;
            default:
                break;
        }
        return $ret;
    }

    public static function getCurrentPageScripts() {
        $ret = "<script src=\"js/lamgmt.js\" type=\"text/javascript\"></script>";
        switch ($GLOBALS['currPage']) {
            case "alumnes":
                $ret .= self::getDataTableScripts();
                $ret .= "<script src=\"js/bsModal.js\" type=\"text/javascript\"></script>";
                $ret .= "<script src=\"js/pages/alumnes.js\" type=\"text/javascript\"></script>";
                break;
            case "alumnesEdit":
                $ret .= "<script src=\"plugins/input-mask/jquery.inputmask.js\" type=\"text/javascript\"></script>\n";
                $ret .= "<script src=\"plugins/input-mask/jquery.inputmask.date.extensions.js\" type=\"text/javascript\"></script>\n";
                $ret .= "<script src=\"js/bsModal.js\" type=\"text/javascript\"></script>";
                $ret .= "<script src=\"js/pages/alumnesEdit.js\" type=\"text/javascript\"></script>";
                break;
            default:
                break;
        }
        return $ret;
    }

    private static function getDataTableScripts() {
        $ret = "<script src=\"plugins/dataTables/dataTables/js/jquery.dataTables.min.js\" type=\"text/javascript\"></script>\n";
        $ret .= "<script src=\"plugins/dataTables/dataTables/js/dataTables.bootstrap.min.js\" type=\"text/javascript\"></script>\n";
        $ret .= "<script src=\"plugins/dataTables/buttons/js/dataTables.buttons.min.js\" type=\"text/javascript\"></script>\n";
        $ret .= "<script src=\"plugins/dataTables/jszip/jszip.min.js\" type=\"text/javascript\"></script>\n";
        $ret .= "<script src=\"plugins/dataTables/pdfmake/pdfmake.min.js\" type=\"text/javascript\"></script>\n";
        $ret .= "<script src=\"plugins/dataTables/pdfmake/vfs_fonts.js\" type=\"text/javascript\"></script>\n";
        $ret .= "<script src=\"plugins/dataTables/buttons/js/buttons.html5.min.js\" type=\"text/javascript\"></script>\n";
        $ret .= "<script src=\"plugins/dataTables/select/js/dataTables.select.min.js\" type=\"text/javascript\"></script>\n";
        return $ret;
    }

    private static function preparePersonalDataArray($srcArray, &$destArray){
        $destArray['id'] = (array_key_exists('id', $srcArray)) ? $srcArray['id'] : 0;
        $destArray['nif'] = (array_key_exists('nif', $srcArray)) ? $srcArray['nif'] : '';
        $destArray['nom'] = (array_key_exists('nom', $srcArray)) ? $srcArray['nom'] : '';
        $destArray['primer_cognom'] = (array_key_exists('primer_cognom', $srcArray)) ? $srcArray['primer_cognom'] : '';
        $destArray['segon_cognom'] = (array_key_exists('segon_cognom', $srcArray)) ? $srcArray['segon_cognom'] : '';
        $destArray['sexe'] = (array_key_exists('sexe', $srcArray)) ? $srcArray['sexe'] : 'M';
        $destArray['mobil'] = (array_key_exists('mobil', $srcArray)) ? $srcArray['mobil'] : '';
        $destArray['telefon'] = (array_key_exists('telefon', $srcArray)) ? $srcArray['telefon'] : '';
        $destArray['email'] = (array_key_exists('email', $srcArray)) ? $srcArray['email'] : '';
        $destArray['adreça'] = (array_key_exists('adreça', $srcArray)) ? $srcArray['adreça'] : '';
        $destArray['codi_postal'] = (array_key_exists('codi_postal', $srcArray)) ? $srcArray['codi_postal'] : '';
        $destArray['poblacio'] = (array_key_exists('poblacio', $srcArray)) ? $srcArray['poblacio'] : '';
        $destArray['provincia'] = (array_key_exists('provincia', $srcArray)) ? $srcArray['provincia'] : '';
        $destArray['data_naixement'] = (array_key_exists('data_naixement', $srcArray)) ? $srcArray['data_naixement'] : '';
        $destArray['data_ingres'] = (array_key_exists('data_ingres', $srcArray)) ? $srcArray['data_ingres'] : '';
        $destArray['data_creacio'] = (array_key_exists('data_creacio', $srcArray)) ? $srcArray['data_creacio'] : '';
        $destArray['data_modificacio'] = (array_key_exists('data_modificacio', $srcArray)) ? $srcArray['data_modificacio'] : '';
        $destArray['esborrat'] = (array_key_exists('esborrat', $srcArray)) ? $srcArray['esborrat'] : 0;
    }
    
    public static function getPersonalDataForm($arrayName, $srcData, $studentFields = true) {
        $data = [];
        if (isset($srcData)){
            self::preparePersonalDataArray($srcData, $data);
        } else {
            self::preparePersonalDataArray($data, $data);
        }

        $ret = '                        <label>Nom complert</label>';
        $ret .= '                        <div class="form-group">';
        $ret .= '                            <div class="row">';
        $ret .= '                                <div class="col-md-4 noPaddingRight">';
        $ret .= '                                    <input name="'.$arrayName.'[primer_cognom]" type="text" class="form-control" placeholder="Primer Cognom" value="'.$data['primer_cognom'].'">';
        $ret .= '                                </div>';
        $ret .= '                                <div class="col-md-4 noPaddingRight padLeft5">';
        $ret .= '                                    <input name="'.$arrayName.'[segon_cognom]" type="text" class="form-control" placeholder="Segon Cognom" value="'.$data['segon_cognom'].'">';
        $ret .= '                                </div>';
        $ret .= '                                <div class="col-md-4 padLeft5">';
        $ret .= '                                    <input name="'.$arrayName.'[nom]" type="text" class="form-control" placeholder="Nom" value="'.$data['nom'].'">';
        $ret .= '                                </div>';
        $ret .= '                            </div>';
        $ret .= '                        </div>';
        $ret .= '                        <div class="row">';
        $ret .= '                            <div class="col-md-3 noPaddingRight">';
        $ret .= '                                <label>NIF</label>';
        $ret .= '                                <div class="form-group">';
        $ret .= '                                    <input name="'.$arrayName.'[nif]" type="text" class="form-control" placeholder="NIF" value="'.$data['nif'].'">';
        $ret .= '                                </div>';
        $ret .= '                            </div>';
        $ret .= '                            <div class="col-md-3 noPaddingRight padLeft5">';
        $ret .= '                                <label>Sexe</label>';
        $ret .= '                                <div class="form-group">';
        $ret .= '                                    <select class="form-control" name="'.$arrayName.'[sexe]">';
        $ret .= '                                        <option'; $ret .= ($data['sexe'] == 'M') ? ' selected ' : ''; $ret .=' value="M">Masculí</option>';
        $ret .= '                                        <option'; $ret .= ($data['sexe'] == 'F') ? ' selected ' : ''; $ret .=' value="F">Femení</option>';
        $ret .= '                                    </select>';
        $ret .= '                                </div>';
        $ret .= '                            </div>';
        $ret .= '                            <div class="col-md-3 noPaddingRight padLeft5">';
        $ret .= '                                <label>Data naixement</label>';
        $ret .= '                                <div class="form-group">';
        $ret .= '                                    <input name="'.$arrayName.'[data_naixement]" type="text" class="form-control"  value="'.$data['data_naixement'].'" data-inputmask="\'alias\': \'dd/mm/yyyy\'" data-mask/>';
        $ret .= '                                </div>';
        $ret .= '                            </div>';
        if ($studentFields) {
            $ret .= '                            <div class="col-md-3 padLeft5">';
            $ret .= '                                <label>Data alta</label>';
            $ret .= '                                <div class="form-group">';
            $ret .= '                                    <input name="'.$arrayName.'[data_ingres]" type="text" class="form-control"  value="'.$data['data_ingres'].'" data-inputmask="\'alias\': \'dd/mm/yyyy\'" data-mask/>';
            $ret .= '                                </div>';
            $ret .= '                            </div>';        
        }
        $ret .= '                        </div>';
        $ret .= '                        <label>Adreça</label>';
        $ret .= '                        <div class="form-group">';
        $ret .= '                            <div class="row">';
        $ret .= '                                <div class="col-md-12">';
        $ret .= '                                    <input name="'.$arrayName.'[adreça]" type="text" class="form-control" placeholder="Adreça" value="'.$data['adreça'].'">';
        $ret .= '                                </div>';
        $ret .= '                            </div>';
        $ret .= '                            <div class="row">';
        $ret .= '                                <div class="col-md-2 noPaddingRight">';
        $ret .= '                                    <input name="'.$arrayName.'[codi_postal]" type="text" class="form-control" placeholder="C. P." size="5"  value="'.$data['codi_postal'].'">';
        $ret .= '                                </div>';
        $ret .= '                                <div class="col-md-5 noPaddingRight padLeft5">';
        $ret .= '                                    <input name="'.$arrayName.'[poblacio]" type="text" class="form-control" placeholder="Població" value="'.$data['poblacio'].'">';
        $ret .= '                                </div>';
        $ret .= '                                <div class="col-md-5 padLeft5">';
        $ret .= '                                    <input name="'.$arrayName.'[provincia]" type="text" class="form-control" placeholder="Provincia" value="'.$data['provincia'].'">';
        $ret .= '                                </div>';
        $ret .= '                            </div>';
        $ret .= '                        </div>';
        $ret .= '                        <label>Dades de contacte</label>';
        $ret .= '                        <div class="form-group">';
        $ret .= '                            <div class="row">';
        $ret .= '                                <div class="col-md-12">';
        $ret .= '                                    <div class="input-group">';
        $ret .= '                                        <div class="input-group-addon">';
        $ret .= '                                            <i class="fa fa-envelope"></i>';
        $ret .= '                                        </div>';
        $ret .= '                                        <input name="'.$arrayName.'[email]" type="email" class="form-control" placeholder="Correu electrònic" value="'.$data['email'].'">';
        $ret .= '                                    </div>';
        $ret .= '                                </div>';
        $ret .= '                            </div>';
        $ret .= '                            <div class="row">';
        $ret .= '                                <div class="col-md-5">';
        $ret .= '                                    <div class="input-group">';
        $ret .= '                                        <div class="input-group-addon">';
        $ret .= '                                            <i class="fa fa-mobile"></i>';
        $ret .= '                                        </div>';
        $ret .= '                                        <input name="'.$arrayName.'[mobil]" type="text" placeholder="Mòbil" class="form-control"  value="'.$data['mobil'].'" data-inputmask=\'"mask": "999999999"\' data-mask/>';
        $ret .= '                                    </div>';
        $ret .= '                                </div>';
        $ret .= '                                <div class="col-md-5 padLeft5">';
        $ret .= '                                    <div class="input-group">';
        $ret .= '                                        <div class="input-group-addon">';
        $ret .= '                                            <i class="fa fa-phone"></i>';
        $ret .= '                                        </div>';
        $ret .= '                                        <input name="'.$arrayName.'[telefon]" type="text" placeholder="Telèfon" class="form-control"  value="'.$data['telefon'].'" data-inputmask=\'"mask": "999999999"\' data-mask/>';
        $ret .= '                                    </div>';
        $ret .= '                                </div>';
        $ret .= '                            </div>';
        $ret .= '                        </div>';
        return $ret;
    }
    
    public static function getModal(){
        //
    }
}
