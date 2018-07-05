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


namespace LAMgmt\Model;

use \DateTime;
use \Exception;
//use \LAMgmt\Validation\AlumneContacteValidator;

/**
 * Model class representing one AlumneContactes item.
 */
final class AlumneContacte {

    /** @var int */
    private $id;
    /** @var int */
    private $id_alumne;
    /** @var int */
    private $id_contacte;
    /** @var string */
    private $relacio;
    /** @var string */
    private $relacio_altres;
    /** @var DateTime */
    private $data_creacio;
    /** @var DateTime */
    private $data_modificacio;
    /** @var bool */
    private $esborrat;

    /**
     * Create new {@link AlumneContacte} with default properties set.
     */
    public function __construct() {
        $now = new DateTime();
        $this->setDataCreacio($now);
        $this->setDataModificacio($now);
        $this->setEsborrat(false);
    }

    //~ Getters & setters

    /**
     * @return int <i>null</i> if not persistent
     */
    public function getId() {
        return $this->id;
    }

    public function setId($value) {
        if ($this->id !== null
                && $this->id != $value) {
            throw new Exception('Cannot change identifier to ' . $value . ', already set to ' . $this->id);
        }
        if ($value === null) {
            $this->id = null;
        } else {
            $this->id = (int) $value;
        }
    }

    /**
     * @return int
     */
    public function getIdAlumne() {
        return $this->id_alumne;
    }

    public function setIdAlumne($value) {
        $this->id_alumne = $value;
    }

    /**
     * @return int
     */
    public function getIdContacte() {
        return $this->id_contacte;
    }

    public function setIdContacte($value) {
        $this->id_contacte = $value;
    }

    /**
     * @return string
     */
    public function getRelacio() {
        return ($this->relacio == null) ? "" : $this->relacio;
    }

    public function setRelacio($value) {
        $this->relacio = $value;
    }

    /**
     * @return string
     */
    public function getRelacioAltres() {
        return ($this->relacio_altres == null) ? "" : $this->relacio_altres;
    }

    public function setRelacioAltres($value) {
        $this->relacio_altres = $value;
    }
    
    /**
     * @return DateTime
     */
    public function getDataCreacio() {
        return $this->data_creacio;
    }

    public function setDataCreacio(DateTime $value) {
        $this->data_creacio = $value;
    }

    /**
     * @return DateTime
     */
    public function getDataModificacio() {
        return $this->data_modificacio;
    }

    public function setDataModificacio(DateTime $value) {
        $this->data_modificacio = $value;
    }

    /**
     * @return bool
     */
    public function getEsborrat() {
        return $this->esborrat;
    }

    public function setEsborrat($value) {
        $this->esborrat = $value;
    }
}
