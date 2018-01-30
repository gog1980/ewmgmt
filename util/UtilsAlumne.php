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

use LAMgmt\Exception\NotFoundException;
use LAMgmt\Dao\AlumneDao;
use LAMgmt\Util\Utils;

/**
 * Miscellaneous utility methods.
 */
final class UtilsAlumne {

    private function __construct() {
    }

    /**
     * Get {@link Alumne} by the identifier 'id' found in the URL.
     * @return Alumne {@link Alumne} instance
     * @throws NotFoundException if the param or {@link Alumne} instance is not found
     */
    public static function getAlumneByGetId() {
        $id = null;
        try {
            $id = Utils::getUrlParam('id');
        } catch (Exception $ex) {
            throw new NotFoundException('No s\'ha indicat identificador d\'alumne .');
        }
        if (!is_numeric($id)) {
            throw new NotFoundException('Identificador d\'alumne invàlid.');
        }
        $dao = new AlumneDao();
        $alumne = $dao->findById($id);
        if ($alumne === null) {
            throw new NotFoundException('Alumne no trobat');
        }
        return $alumne;
    } 
}
