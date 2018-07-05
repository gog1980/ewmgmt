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

namespace LAMgmt\Dao;

/**
 * Search criteria for {@link TodoDao}.
 * <p>
 * Can be easily extended without changing the {@link TodoDao} API.
 */
final class ContacteSearchCriteria {

   /** @var string */
    private $nif;
    /** @var string */
    private $nom;
    /** @var string */
    private $primer_cognom;
    /** @var string */
    private $segon_cognom;

    /**
     * @return string
     */
    public function getNif() {
        return $this->nif;
    }

    /**
     * 
     * @param type $value
     * @return $this
     */
    public function setNif($value) {
        $this->nif = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getNom() {
        return $this->nom;
    }

    /**
     * 
     * @param type $value
     * @return $this
     */
    public function setNom($value) {
        $this->nom = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrimerCognom() {
        return $this->primer_cognom;
    }

    /**
     * 
     * @param type $value
     * @return $this
     */
    public function setPrimerCognom($value) {
        $this->primer_cognom = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getSegonCognom() {
        return $this->segon_cognom;
    }

    /**
     * 
     * @param type $value
     * @return $this
     */
    public function setSegonCognom($value) {
        $this->segon_cognom = $value;
        return $this;
    }

}
