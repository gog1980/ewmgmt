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

use \DateTime;
use \Exception;
use \PDO;
use \PDOStatement;
use \LAMgmt\Config\Config;
use \LAMgmt\Exception\NotFoundException;
use \LAMgmt\Mapping\ContacteMapper;
use \LAMgmt\Model\Contacte;


/**
 * DAO for {@link \LAMgmt\Model\Contactes}.
 */
final class ContacteDao{
    /** @var PDO */
    private $db = null;
    
    public function __destruct(){
        $this->db = null;
    }
    
    /**
     * Find all {@link Contactes}s by search criteria.
     * @return array array of {@link Contactes}s
     */
    public function find(ContacteSearchCriteria $search = null) {
        $result = [];
        foreach ($this->query($this->getFindSql($search)) as $row) {
            $contacte = new Contacte();
            ContacteMapper::map($contacte, $row);
            $result[$contacte->getId()] = $contacte;
        }
        return $result;
    }

    /**
     * Find {@link Contacte} by identifier.
     * @return Contacte Contacte or <i>null</i> if not found
     */
    public function findById($id) {
        $row = $this->query('SELECT * FROM contactes WHERE esborrat = 0 and id = ' . (int) $id)->fetch();
        if (!$row) {
            return null;
        }
        $contacte = new Contacte();
        ContacteMapper::map($contacte, $row);
        return $contacte;
    }
    
    /**
     * Find {@link Contacte} by identifier.
     * @return Contacte[] Contacte or <i>null</i> if not found
     */
    public function findByAlumneId($id) {
        $res = $this->query('SELECT * FROM contactes WHERE esborrat = 0 and id in (select id_contacte from alumnes_contactes where id_alumne = ' . (int)$id . ')');
        if (!$res) {
            return null;
        }
        $contactes = array();
        while ($row = $res->fetch()) {
            $contacte = new Contacte();
            ContacteMapper::map($contacte, $row);
            array_push($contactes, $contacte);
        }
        return $contactes;
    }    
    
    /**
     * Find {@link Contacte} by identifier.
     * @return Contacte[] Contacte or <i>null</i> if not found
     */
    public function getContacteForAlumneEditByAlumneId($id) {
        $res = $this->query('select contactes.*, alumnes_contactes.relacio, alumnes_contactes.relacio_altres from contactes join alumnes_contactes on contactes.id = alumnes_contactes.id_contacte WHERE contactes.esborrat = 0 and alumnes_contactes.id_alumne = ' . (int)$id);
        if (!$res) {
            return null;
        }
        $contactes = array();
        while ($row = $res->fetch()) {
            $contacte = new Contacte();
            ContacteMapper::map($contacte, $row);
            array_push($contactes, $contacte);
        }
        return $contactes;
    }        

    /**
     * Save {@link Contacte}.
     * @param Contacte $contacte {@link Contacte} to be saved
     * @return Contacte saved {@link Contacte} instance
     */
    public function save(Contacte $contacte) {
        if ($contacte->getId() === null) {
            return $this->insert($contacte);
        }
        return $this->update($contacte);
    }

    /**
     * Delete {@link Contacte} by identifier.
     * @param int $id {@link Contacte} identifier
     * @return bool <i>true</i> on success, <i>false</i> otherwise
     */
    public function delete($id) {
        $sql = '
            UPDATE contactes SET
                data_modificacio = :data_modificacio,
                esborrat = :esborrat
            WHERE
                id = :id';
        $statement = $this->getDb()->prepare($sql);
        $this->executeStatement($statement, [
            ':data_modificacio' => self::formatDateTime(new DateTime()),
            ':esborrat' => true,
            ':id' => $id,
        ]);
        return $statement->rowCount() == 1;
    }

    /**
     * @return PDO
     */
    private function getDb() {
        if ($this->db !== null) {
            return $this->db;
        }
        $config = Config::getConfig('db');
        try {
            $this->db = new PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4", $config['user'], $config['password'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        } catch (Exception $ex) {
            throw new Exception('DB connection error: ' . $ex->getMessage());
        }
        return $this->db;
    }

    private function getFindSql(ContacteSearchCriteria $search = null) {
        $sql = 'SELECT * FROM contactes WHERE deleted = 0 ';
        $orderBy = '';
        if ($search !== null) {
            /*if ($search->getStatus() !== null) {
                $sql .= 'AND status = ' . $this->getDb()->quote($search->getStatus());
                switch ($search->getStatus()) {
                    case Todo::STATUS_PENDING:
                        $orderBy = 'due_on, priority';
                        break;
                    case Todo::STATUS_DONE:
                    case Todo::STATUS_VOIDED:
                        $orderBy = 'due_on DESC, priority';
                        break;
                    default:
                        throw new Exception('No order for status: ' . $search->getStatus());
                }
            }*/
        }
        $sql .= ' ORDER BY ' . $orderBy;
        return $sql;
    }

    /**
     * @return Contacte
     * @throws Exception
     */
    private function insert(Contacte $contacte) {
        $now = new DateTime();
        $contacte->setId(null);
        $contacte->setDataCreacio($now);
        $contacte->setDataModificacio($now);
        $sql = '
            INSERT INTO `contactes` (`nif`, `nom`, `primer_cognom`, `segon_cognom`, `sexe`, `mobil`, `telefon`, `email`, `adreça`, `codi_postal`, `poblacio`, `provincia`, `data_naixement`, `data_creacio`, `data_modificacio`, `esborrat`) 
            VALUES (:nif, :nom, :primer_cognom, :segon_cognom, :sexe, :mobil, :telefon, :email, :adreca, :codi_postal, :poblacio, :provincia, :data_naixement, :data_creacio, :data_modificacio, :esborrat);';
        return $this->execute($sql, $contacte);
    }

    /**
     * @return Contacte
     * @throws Exception
     */
    private function update(Contacte $contacte) {
        $contacte->setDataModificacio(new DateTime());
        $sql = '
            UPDATE `contactes` SET
                `nif` = :nif, 
                `nom` = :nom, 
                `primer_cognom` = :primer_cognom, 
                `segon_cognom` = :segon_cognom, 
                `sexe` = :sexe, 
                `mobil` = :mobil, 
                `telefon` = :telefon, 
                `email` = :email, 
                `adreça` = :adreca, 
                `codi_postal` = :codi_postal, 
                `poblacio` = :poblacio, 
                `provincia` = :provincia, 
                `data_naixement` = :data_naixement, 
                `data_modificacio` = :data_modificacio, 
                `esborrat` = :esborrat
            WHERE
                `id` = :id';
        return $this->execute($sql, $contacte);
    }

    /**
     * @return Contacte
     * @throws Exception
     */
    private function execute($sql, Contacte $contacte) {
        $statement = $this->getDb()->prepare($sql);
        $this->executeStatement($statement, $this->getParams($contacte));
        if (!$contacte->getId()) {
            return $this->findById($this->getDb()->lastInsertId());
        }
        if ($statement->errorCode() != 0) {
            throw new Exception("Error al grabar el contacte: ".$statement->errorInfo()) ;
        }
        return $contacte;
    }

    private function getParams(Contacte $contacte) {
        $params = [
            ':nif' => $contacte->getNif(),
            ':nom' => $contacte->getNom(),
            ':primer_cognom' => $contacte->getPrimerCognom(),
            ':segon_cognom' => $contacte->getSegonCognom(),
            ':sexe' => $contacte->getSexe(),
            ':mobil' => $contacte->getMobil(),
            ':telefon' => $contacte->getTelefon(),
            ':email' => $contacte->getEmail(),
            ':adreca' => $contacte->getAdreça(),
            ':codi_postal' => $contacte->getCodiPostal(),
            ':poblacio' => $contacte->getPoblacio(),
            ':provincia' => $contacte->getProvincia(),
            ':data_naixement' => self::formatDateTime($contacte->getDataNaixement()),
            ':data_creacio' => self::formatDateTime($contacte->getDataCreacio()),
            ':data_modificacio' => self::formatDateTime($contacte->getDataModificacio()),
            ':esborrat' => self::formatBoolean($contacte->getEsborrat())
        ];
        if ($contacte->getId()) {
            // unset data_creacio, this one is never updated
            unset($params[':data_creacio']);
            $params[':id'] = $contacte->getId();
        } 
        return $params;
    }

    private function executeStatement(PDOStatement $statement, array $params) {
        // XXX
        //echo str_replace(array_keys($params), $params, $statement->queryString) . PHP_EOL;
        if ($statement->execute($params) === false) {
            self::throwDbError($this->getDb()->errorInfo());
        }
    }

    /**
     * @return PDOStatement
     */
    private function query($sql) {
        $statement = $this->getDb()->query($sql, PDO::FETCH_ASSOC);
        if ($statement === false) {
            self::throwDbError($this->getDb()->errorInfo());
        }
        return $statement;
    }

    private static function throwDbError(array $errorInfo) {
        // TODO log error, send email, etc.
        throw new Exception('DB error [' . $errorInfo[0] . ', ' . $errorInfo[1] . ']: ' . $errorInfo[2]);
    }

    private static function formatDateTime(DateTime $date) {
        return $date->format('Y-m-d H:i:s');
    }

    private static function formatBoolean($bool) {
        return $bool ? 1 : 0;
    }

}
