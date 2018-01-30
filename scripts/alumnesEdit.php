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

namespace LAMgmt;

use \DateTime;
use \LAMgmt\Dao\AlumneDao;
use \LAMgmt\Mapping\AlumneMapper;
use \LAMgmt\Model\Alumne;
use \LAMgmt\Util\UtilsAlumne;
use \LAMgmt\Util\Utils;
//use \TodoList\Validation\TodoValidator;

$errors = [];
$alumne = null;
$alumneValues = [];
$edit = array_key_exists('id', $_GET);
if ($edit) {
    $alumne = UtilsAlumne::getAlumneByGetId();
    AlumneMapper::mapToArray($alumneValues, $alumne);
} else {
    // set defaults
    $alumne = new Alumne();
}

if (array_key_exists('guardar', $_POST)) {
    // for security reasons, do not map the whole $_POST['todo']
    /*$data = [
        'title' => $_POST['todo']['title'],
        'due_on' => $_POST['todo']['due_on_date'] . ' ' . $_POST['todo']['due_on_hour'] . ':' . $_POST['todo']['due_on_minute'] . ':00',
        'priority' => $_POST['todo']['priority'],
        'description' => $_POST['todo']['description'],
        'comment' => $_POST['todo']['comment'],
    ];*/
    // map
    AlumneMapper::map($alumne, $_POST['alumne']);
    // validate
    //$errors = TodoValidator::validate($alumne);
    // validate
    if (empty($errors)) {
        // save
        $dao = new AlumneDao();
        $alumne = $dao->save($alumne);
        //Flash::addFlash('TODO saved successfully.');
        // redirect
        if (isset($alumne)){
            Utils::redirect('alumnesEdit', ['id' => $alumne->getId()]);
        }
    }
}
