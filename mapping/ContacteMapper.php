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
use \LAMgmt\Model\Contacte;

/**
 * Mapper for {@link \LAMgmt\Model\Contacte} from array.
 * @see \LAMgmt\Validation\ContacteValidator
 */
final class ContacteMapper {

    private function __construct() {
    }

    /**
     * Maps array to the given {@link Contacte}.
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
     *   <li>data_creacio</li>
     *   <li>data_modificacio</li>
     *   <li>esborrat</li>
     *   <li>relacio</li>
     *   <li>relacio_altres</li>
     * </ul>
     * @param Contacte $contacte
     * @param array $properties
     */
    public static function map(Contacte $contacte, array $properties) {
        if (array_key_exists('id', $properties)) {
            $contacte->setId($properties['id']);
        }
        if (array_key_exists('nif', $properties)) {
            $contacte->setNif($properties['nif']);
        }
        if (array_key_exists('nom', $properties)) {
            $contacte->setNom($properties['nom']);
        }
        if (array_key_exists('primer_cognom', $properties)) {
            $contacte->setPrimerCognom($properties['primer_cognom']);
        }
        if (array_key_exists('segon_cognom', $properties)) {
            $contacte->setSegonCognom($properties['segon_cognom']);
        }
        if (array_key_exists('sexe', $properties)) {
            $contacte->setSexe($properties['sexe']);
        }
        if (array_key_exists('mobil', $properties)) {
            $contacte->setMobil($properties['mobil']);
        }
        if (array_key_exists('telefon', $properties)) {
            $contacte->setTelefon($properties['telefon']);
        }
        if (array_key_exists('email', $properties)) {
            $contacte->setEmail($properties['email']);
        }
        if (array_key_exists('adreça', $properties)) {
            $contacte->setAdreça($properties['adreça']);
        }
        if (array_key_exists('codi_postal', $properties)) {
            $contacte->setCodiPostal($properties['codi_postal']);
        }
        if (array_key_exists('poblacio', $properties)) {
            $contacte->setPoblacio($properties['poblacio']);
        }
        if (array_key_exists('provincia', $properties)) {
            $contacte->setProvincia($properties['provincia']);
        }
        if (array_key_exists('data_naixement', $properties)) {
            $value = self::createDateTime($properties['data_naixement']);
            if ($value) {
                $contacte->setDataNaixement($value);
            }
        }
        if (array_key_exists('data_creacio', $properties)) {
            $value = self::createDateTime($properties['data_creacio']);
            if ($value) {
                $contacte->setDataCreacio($value);
            }
        }
        if (array_key_exists('data_modificacio', $properties)) {
            $value = self::createDateTime($properties['data_modificacio']);
            if ($value) {
                $contacte->setDataModificacio($value);
            }
        }
        if (array_key_exists('esborrat', $properties)) {
            $contacte->setEsborrat($properties['esborrat']);
        }
        
        /* Alumnes_Contactes properties. Used for agility purposes. */
        
        if (array_key_exists('relacio', $properties)) {
            $contacte->setRelacio($properties['relacio']);
        }
        
        if (array_key_exists('relacio_altres', $properties)) {
            $contacte->setRelacioAltres($properties['relacio_altres']);
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
     * Maps the given {@link Contacte} to the given array.
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
     *   <li>data_creacio</li>
     *   <li>data_modificacio</li>
     *   <li>esborrat</li>
     *   <li>relacio</li>
     *   <li>relacio_altres</li>
     * </ul>
     * @param array $arrDest
     * @param Contacte $contacte
     */
    public static function mapToArray(array &$arrDest, Contacte $contacte) {    
        $arrDest['id'] = $contacte->getId();
        $arrDest['nif'] = $contacte->getNif();
        $arrDest['nom'] = $contacte->getNom();
        $arrDest['primer_cognom'] = $contacte->getPrimerCognom();
        $arrDest['segon_cognom'] = $contacte->getSegonCognom();
        $arrDest['sexe'] = $contacte->getSexe();
        $arrDest['mobil'] = $contacte->getMobil();
        $arrDest['telefon'] = $contacte->getTelefon();
        $arrDest['email'] = $contacte->getEmail();
        $arrDest['adreça'] = $contacte->getAdreça();
        $arrDest['codi_postal'] = $contacte->getCodiPostal();
        $arrDest['poblacio'] = $contacte->getPoblacio();
        $arrDest['provincia'] = $contacte->getProvincia();
        $arrDest['data_naixement'] = self::getDate($contacte->getDataNaixement());
        $arrDest['data_creacio'] = self::getDate($contacte->getDataCreacio());
        $arrDest['data_modificacio'] = self::getDate($contacte->getDataModificacio());
        $arrDest['esborrat'] = $contacte->getEsborrat();
        
        /* Alumnes_Contactes properties. Used for agility purposes. */
        $arrDest['relacio'] = $contacte->getRelacio();
        $arrDest['relacio_altres'] = $contacte->getRelacioAltres();
    }
    
    private static function getDate($value)
    {
        if ($value){
            return $value->format('d/m/Y');
        } else {
            return "";
        }
    }    
}
