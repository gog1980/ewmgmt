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
use \LAMgmt\Model\AlumneContacte;

/**
 * Mapper for {@link \LAMgmt\Model\AlumneContacte} from array.
 * @see \LAMgmt\Validation\AlumneContacteValidator
 */
final class AlumneContacteMapper {

    private function __construct() {
    }

    /**
     * Maps array to the given {@link AlumneContacte}.
     * <p>
     * Expected properties are:
     * <ul>
     *   <li>id</li>
     *   <li>id_alumne</li>
     *   <li>id_contacte</li>
     *   <li>relacio</li>
     *   <li>relacio_altres</li>
     *   <li>data_creacio</li>
     *   <li>data_modificacio</li>
     *   <li>esborrat</li>
     * </ul>
     * @param Todo $alumneContacte
     * @param array $properties
     */
    public static function map(AlumneContacte $alumneContacte, array $properties) {
        if (array_key_exists('id', $properties)) {
            $alumneContacte->setId($properties['id']);
        }
        if (array_key_exists('id_alumne', $properties)) {
            $alumneContacte->setIdAlumne($properties['id_alumne']);
        }
        if (array_key_exists('id_contacte', $properties)) {
            $alumneContacte->setIdContacte($properties['id_contacte']);
        }
        if (array_key_exists('relacio', $properties)) {
            $alumneContacte->setRelacio($properties['relacio']);
        }
        if (array_key_exists('relacio_altres', $properties)) {
            $alumneContacte->setRelacioAltres($properties['relacio_altres']);
        }
        if (array_key_exists('data_creacio', $properties)) {
            $value = self::createDateTime($properties['data_creacio']);
            if ($value) {
                $alumneContacte->setDataCreacio($value);
            }
        }
        if (array_key_exists('data_modificacio', $properties)) {
            $value = self::createDateTime($properties['data_modificacio']);
            if ($value) {
                $alumneContacte->setDataModificacio($value);
            }
        }
        if (array_key_exists('esborrat', $properties)) {
            $alumneContacte->setEsborrat($properties['esborrat']);
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
     * Maps the given {@link AlumneContacte} to the given array.
     * <p>
     * Expected returned values in array are:
     * <ul>
     *   <li>id</li>
     *   <li>id_alumne</li>
     *   <li>id_contacte</li>
     *   <li>relacio</li>
     *   <li>relacio_altres</li>
     *   <li>data_creacio</li>
     *   <li>data_modificacio</li>
     *   <li>esborrat</li>
     * </ul>
     * @param array $arrDest
     * @param AlumneContacte $alumneContacte
     */
    public static function mapToArray(array &$arrDest, AlumneContacte $alumneContacte) {    
        $arrDest['id'] = $alumneContacte->getId();
        $arrDest['id_alumne'] = $alumneContacte->getIdAlumne();
        $arrDest['id_contacte'] = $alumneContacte->getIdContacte();
        $arrDest['relacio'] = $alumneContacte->getRelacio();
        $arrDest['relacio_altres'] = $alumneContacte->getRelacioAltres();
        $arrDest['data_creacio'] = $alumneContacte->getDataCreacio()->format('d/m/Y');
        $arrDest['data_modificacio'] = $alumneContacte->getDataModificacio()->format('d/m/Y');
        $arrDest['esborrat'] = $alumneContacte->getEsborrat();
    }
}
