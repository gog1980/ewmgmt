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
//use \LAMgmt\Validation\ContacteValidator;

/**
 * Model class representing one Contactes item.
 */
final class Contacte {

    /** @var int */
    private $id;
    /** @var string */
    private $nif;
    /** @var string */
    private $nom;
    /** @var string */
    private $primer_cognom;
    /** @var string */
    private $segon_cognom;
    /** @var string */
    private $sexe;
    /** @var string */
    private $mobil;
    /** @var string */
    private $telefon;
    /** @var string */
    private $email;
    /** @var string */
    private $adreça;
    /** @var string */
    private $codi_postal;
    /** @var string */
    private $poblacio;
    /** @var string */
    private $provincia;
    /** @var DateTime */
    private $data_naixement;
    /** @var DateTime */
    private $data_creacio;
    /** @var DateTime */
    private $data_modificacio;
    /** @var bool */
    private $esborrat;
    /** @var string */
    private $relacio;
    /** @var string */
    private $relacioAltres;    

    /**
     * Create new {@link Contacte} with default properties set.
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
     * @return string
     */
    public function getNif() {
        return $this->nif;
    }

    public function setNif($value) {
        $this->nif = $value;
    }

    /**
     * @return string
     */
    public function getNom() {
        return $this->nom;
    }

    public function setNom($value) {
        $this->nom = $value;
    }

    /**
     * @return string
     */
    public function getPrimerCognom() {
        return $this->primer_cognom;
    }

    public function setPrimerCognom($value) {
        $this->primer_cognom = $value;
    }

    /**
     * @return string
     */
    public function getSegonCognom() {
        return $this->segon_cognom;
    }

    public function setSegonCognom($value) {
        $this->segon_cognom = $value;
    }

    /**
     * @return string one of M/F
     */
    public function getSexe() {
        return $this->sexe;
    }

    public function setSexe($value) {
        $this->sexe = $value;
    }

    /**
     * @return string
     */
    public function getMobil() {
        return $this->mobil;
    }

    public function setMobil($value) {
        $this->mobil = $value;
    }

    /**
     * @return string
     */
    public function getTelefon() {
        return $this->telefon;
    }

    public function setTelefon($value) {
        $this->telefon = $value;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    public function setEmail($value) {
        $this->email = $value;
    }

    /**
     * @return string
     */
    public function getAdreça() {
        return $this->adreça;
    }

    public function setAdreça($value) {
        $this->adreça = $value;
    }

    /**
     * @return string
     */
    public function getCodiPostal() {
        return $this->codi_postal;
    }

    public function setCodiPostal($value) {
        $this->codi_postal = $value;
    }

    /**
     * @return string
     */
    public function getPoblacio() {
        return $this->poblacio;
    }

    public function setPoblacio($value) {
        $this->poblacio = $value;
    }

    /**
     * @return string
     */
    public function getProvincia() {
        return $this->provincia;
    }

    public function setProvincia($value) {
        $this->provincia = $value;
    }

    /**
     * @return DateTime
     */
    public function getDataNaixement() {
        return $this->data_naixement;
    }

    public function setDataNaixement(DateTime $value) {
        $this->data_naixement = $value;
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
    
    /* Alumnes_Contactes properties. Used for agility purposes. */
    
    /**
     * @return string
     */
    public function getRelacio() {
        return $this->relacio;
    }

    public function setRelacio($value) {
        $this->relacio = $value;
    }
    
    /**
     * @return string
     */
    public function getRelacioAltres() {
        return $this->relacioAltres;
    }

    public function setRelacioAltres($value) {
        $this->relacioAltres = $value;
    }    
    
}
