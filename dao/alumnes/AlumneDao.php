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
use \LAMgmt\Mapping\AlumneMapper;
use \LAMgmt\Model\Alumne;


/**
 * DAO for {@link \LAMgmt\Model\Alumnes}.
 */
final class AlumneDao{
    /** @var PDO */
    private $db = null;
    
    public function __destruct(){
        $this->db = null;
    }
    
    /**
     * Find all {@link Alumnes}s by search criteria.
     * @return array array of {@link Alumnes}s
     */
    public function find(AlumneSearchCriteria $search = null) {
        $result = [];
        foreach ($this->query($this->getFindSql($search)) as $row) {
            $alumne = new Alumne();
            AlumneMapper::map($alumne, $row);
            $result[$alumne->getId()] = $alumne;
        }
        return $result;
    }

    /**
     * Find {@link Alumne} by identifier.
     * @return Alumne Alumne or <i>null</i> if not found
     */
    public function findById($id) {
        $row = $this->query('SELECT * FROM alumnes WHERE esborrat = 0 and id = ' . (int) $id)->fetch();
        if (!$row) {
            return null;
        }
        $alumne = new Alumne();
        AlumneMapper::map($alumne, $row);
        return $alumne;
    }

    /**
     * Save {@link Alumne}.
     * @param Alumne $alumne {@link Alumne} to be saved
     * @return Alumne saved {@link Alumne} instance
     */
    public function save(Alumne $alumne) {
        if ($alumne->getId() === null) {
            return $this->insert($alumne);
        }
        return $this->update($alumne);
    }

    /**
     * Delete {@link Alumne} by identifier.
     * @param int $id {@link Alumne} identifier
     * @return bool <i>true</i> on success, <i>false</i> otherwise
     */
    public function delete($id) {
        $sql = '
            UPDATE alumnes SET
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

    private function getFindSql(AlumneSearchCriteria $search = null) {
        $sql = 'SELECT * FROM alumnes WHERE deleted = 0 ';
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
     * @return Alumne
     * @throws Exception
     */
    private function insert(Alumne $alumne) {
        $now = new DateTime();
        $alumne->setId(null);
        $alumne->setDataCreacio($now);
        $alumne->setDataModificacio($now);
        $sql = '
            INSERT INTO `alumnes` (`nif`, `nom`, `primer_cognom`, `segon_cognom`, `sexe`, `mobil`, `telefon`, `email`, `adreça`, `codi_postal`, `poblacio`, `provincia`, `data_naixement`, `data_ingres`, `data_creacio`, `data_modificacio`, `esborrat`) 
            VALUES (:nif, :nom, :primer_cognom, :segon_cognom, :sexe, :mobil, :telefon, :email, :adreca, :codi_postal, :poblacio, :provincia, :data_naixement, :data_ingres, :data_creacio, :data_modificacio, :esborrat);';
        return $this->execute($sql, $alumne);
    }

    /**
     * @return Alumne
     * @throws Exception
     */
    private function update(Alumne $alumne) {
        $alumne->setDataModificacio(new DateTime());
        $sql = '
            UPDATE `alumnes` SET
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
                `data_ingres` = :data_ingres, 
                `data_modificacio` = :data_modificacio, 
                `esborrat` = :esborrat
            WHERE
                `id` = :id';
        return $this->execute($sql, $alumne);
    }

    /**
     * @return Alumne
     * @throws Exception
     */
    private function execute($sql, Alumne $alumne) {
        $statement = $this->getDb()->prepare($sql);
        $this->executeStatement($statement, $this->getParams($alumne));
        if (!$alumne->getId()) {
            return $this->findById($this->getDb()->lastInsertId());
        }
        if ($statement->errorCode() != 0) {
            throw new Exception("Error al grabar l'alumne: ".$statement->errorInfo()) ;
        }
        return $alumne;
    }

    private function getParams(Alumne $alumne) {
        $params = [
            ':nif' => $alumne->getNif(),
            ':nom' => $alumne->getNom(),
            ':primer_cognom' => $alumne->getPrimerCognom(),
            ':segon_cognom' => $alumne->getSegonCognom(),
            ':sexe' => $alumne->getSexe(),
            ':mobil' => $alumne->getMobil(),
            ':telefon' => $alumne->getTelefon(),
            ':email' => $alumne->getEmail(),
            ':adreca' => $alumne->getAdreça(),
            ':codi_postal' => $alumne->getCodiPostal(),
            ':poblacio' => $alumne->getPoblacio(),
            ':provincia' => $alumne->getProvincia(),
            ':data_naixement' => self::formatDateTime($alumne->getDataNaixement()),
            ':data_ingres' => self::formatDateTime($alumne->getDataIngres()),
            ':data_creacio' => self::formatDateTime($alumne->getDataCreacio()),
            ':data_modificacio' => self::formatDateTime($alumne->getDataModificacio()),
            ':esborrat' => self::formatBoolean($alumne->getEsborrat())
        ];
        if ($alumne->getId()) {
            // unset data_creacio, this one is never updated
            unset($params[':data_creacio']);
            $params[':id'] = $alumne->getId();
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
