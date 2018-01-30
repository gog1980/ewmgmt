<?php

/* 
 * Copyright 2017 Oscar.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace LAMgmt\Mapping;

use \DateTime;
use \LAMgmt\Model\Alumne;

/**
 * Mapper for {@link \LAMgmt\Model\Alumne} from array.
 * @see \LAMgmt\Validation\AlumneValidator
 */
final class AlumneMapper {

    private function __construct() {
    }

    /**
     * Maps array to the given {@link Alumne}.
     * <p>
     * Expected properties are:
     * <ul>
     *   <li>id</li>
     *   <li>nif</li>
     *   <li>nom</li>
     *   <li>primer_cognom</li>
     *   <li>segon_cognom</li>
     *   <li>sexe</li>
     *   <li>mobil</li>
     *   <li>telefon</li>
     *   <li>email</li>
     *   <li>adreça</li>
     *   <li>codi_postal</li>
     *   <li>poblacio</li>
     *   <li>provincia</li>
     *   <li>data_naixement</li>
     *   <li>data_ingres</li>
     *   <li>data_creacio</li>
     *   <li>data_modificacio</li>
     *   <li>esborrat</li>
     * </ul>
     * @param Todo $alumne
     * @param array $properties
     */
    public static function map(Alumne $alumne, array $properties) {
        if (array_key_exists('id', $properties)) {
            $alumne->setId($properties['id']);
        }
        if (array_key_exists('nif', $properties)) {
            $alumne->setNif($properties['nif']);
        }
        if (array_key_exists('nom', $properties)) {
            $alumne->setNom($properties['nom']);
        }
        if (array_key_exists('primer_cognom', $properties)) {
            $alumne->setPrimerCognom($properties['primer_cognom']);
        }
        if (array_key_exists('segon_cognom', $properties)) {
            $alumne->setSegonCognom($properties['segon_cognom']);
        }
        if (array_key_exists('sexe', $properties)) {
            $alumne->setSexe($properties['sexe']);
        }
        if (array_key_exists('mobil', $properties)) {
            $alumne->setMobil($properties['mobil']);
        }
        if (array_key_exists('telefon', $properties)) {
            $alumne->setTelefon($properties['telefon']);
        }
        if (array_key_exists('email', $properties)) {
            $alumne->setEmail($properties['email']);
        }
        if (array_key_exists('adreça', $properties)) {
            $alumne->setAdreça($properties['adreça']);
        }
        if (array_key_exists('codi_postal', $properties)) {
            $alumne->setCodiPostal($properties['codi_postal']);
        }
        if (array_key_exists('poblacio', $properties)) {
            $alumne->setPoblacio($properties['poblacio']);
        }
        if (array_key_exists('provincia', $properties)) {
            $alumne->setProvincia($properties['provincia']);
        }
        if (array_key_exists('data_naixement', $properties)) {
            $value = self::createDateTime($properties['data_naixement']);
            if ($value) {
                $alumne->setDataNaixement($value);
            }
        }
        if (array_key_exists('data_ingres', $properties)) {
            $value = self::createDateTime($properties['data_ingres']);
            if ($value) {
                $alumne->setDataIngres($value);
            }
        }        
        if (array_key_exists('data_creacio', $properties)) {
            $value = self::createDateTime($properties['data_creacio']);
            if ($value) {
                $alumne->setDataCreacio($value);
            }
        }
        if (array_key_exists('data_modificacio', $properties)) {
            $value = self::createDateTime($properties['data_modificacio']);
            if ($value) {
                $alumne->setDataModificacio($value);
            }
        }
        if (array_key_exists('esborrat', $properties)) {
            $alumne->setEsborrat($properties['esborrat']);
        }
    }

    private static function createDateTime($input) {
        $pos = strpos($input,"/");
        if (!($pos)){
            $pos = strpos($input,"-");
        }
        if ($pos > 2){
            return DateTime::createFromFormat('Y-m-d', $input);
        } else {
            return DateTime::createFromFormat('d/m/Y', $input);
        }
    }
    
    /**
     * Maps the given {@link Alumne} to the given array.
     * <p>
     * Expected returned values in array are:
     * <ul>
     *   <li>id</li>
     *   <li>nif</li>
     *   <li>nom</li>
     *   <li>primer_cognom</li>
     *   <li>segon_cognom</li>
     *   <li>sexe</li>
     *   <li>mobil</li>
     *   <li>telefon</li>
     *   <li>email</li>
     *   <li>adreça</li>
     *   <li>codi_postal</li>
     *   <li>poblacio</li>
     *   <li>provincia</li>
     *   <li>data_naixement</li>
     *   <li>data_ingres</li>
     *   <li>data_creacio</li>
     *   <li>data_modificacio</li>
     *   <li>esborrat</li>
     * </ul>
     * @param array $arrDest
     * @param Alumne $alumne
     */
    public static function mapToArray(array &$arrDest, Alumne $alumne) {    
        $arrDest['id'] = $alumne->getId();
        $arrDest['nif'] = $alumne->getNif();
        $arrDest['nom'] = $alumne->getNom();
        $arrDest['primer_cognom'] = $alumne->getPrimerCognom();
        $arrDest['segon_cognom'] = $alumne->getSegonCognom();
        $arrDest['sexe'] = $alumne->getSexe();
        $arrDest['mobil'] = $alumne->getMobil();
        $arrDest['telefon'] = $alumne->getTelefon();
        $arrDest['email'] = $alumne->getEmail();
        $arrDest['adreça'] = $alumne->getAdreça();
        $arrDest['codi_postal'] = $alumne->getCodiPostal();
        $arrDest['poblacio'] = $alumne->getPoblacio();
        $arrDest['provincia'] = $alumne->getProvincia();
        $arrDest['data_naixement'] = $alumne->getDataNaixement()->format('d/m/Y');
        $arrDest['data_ingres'] = $alumne->getDataIngres()->format('d/m/Y');
        $arrDest['data_creacio'] = $alumne->getDataCreacio()->format('d/m/Y');
        $arrDest['data_modificacio'] = $alumne->getDataModificacio()->format('d/m/Y');
        $arrDest['esborrat'] = $alumne->getEsborrat();
    }
}
