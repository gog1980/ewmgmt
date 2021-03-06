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
use \LAMgmt\Mapping\AlumneContacteMapper;
use \LAMgmt\Model\AlumneContacte;


/**
 * DAO for {@link \LAMgmt\Model\AlumneContacte}.
 */
final class AlumneContacteDao{
    /** @var PDO */
    private $db = null;
    
    public function __destruct(){
        $this->db = null;
    }
    
    /**
     * Find all {@link AlumneContacte}s by IdAlumne.
     * @return array of {@link AlumneContacte}s
     */
    public function findByIdAlumne($idAlumne) {
        $result = [];
        foreach ($this->query('SELECT * FROM alumnes_contactes WHERE esborrat = 0 and id_alumne = ' . (int) $idAlumne) as $row) {
            $alumneContacte = new AlumneContacte();
            AlumneContacteMapper::map($alumneContacte, $row);
            $result[$alumneContacte->getId()] = $alumneContacte;
        }
        return $result;
    }
    
    /**
     * Find all {@link AlumneContacte}s by IdAlumne.
     * @return array of {@link AlumneContacte}s
     */
    public function findByIdContacte($idContacte) {
        $result = [];
        foreach ($this->query('SELECT * FROM alumnes_contactes WHERE esborrat = 0 and id_contacte = ' . (int) $idContacte) as $row) {
            $alumneContacte = new AlumneContacte();
            AlumneContacteMapper::map($alumneContacte, $row);
            $result[$alumneContacte->getId()] = $alumneContacte;
        }
        return $result;
    }    

    /**
     * Find {@link AlumneContacte} by identifier.
     * @return AlumneContacte AlumneContacte or <i>null</i> if not found
     */
    public function findById($id) {
        $row = $this->query('SELECT * FROM alumnes_contactes WHERE esborrat = 0 and id = ' . (int) $id)->fetch();
        if (!$row) {
            return null;
        }
        $alumneContacte = new AlumneContacte();
        AlumneContacteMapper::map($alumneContacte, $row);
        return $alumneContacte;
    }

    /**
     * Save {@link AlumneContacte}.
     * @param AlumneContacte $alumneContacte {@link AlumneContacte} to be saved
     * @return AlumneContacte saved {@link AlumneContacte} instance
     */
    public function save(AlumneContacte $alumneContacte) {
        if ($alumneContacte->getId() === null) {
            return $this->insert($alumneContacte);
        }
        return $this->update($alumneContacte);
    }

    /**
     * Delete {@link AlumneContacte} by identifier.
     * @param int $id {@link AlumneContacte} identifier
     * @return bool <i>true</i> on success, <i>false</i> otherwise
     */
    public function delete($id) {
        $sql = '
            UPDATE alumnes_contactes SET
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

    private function getFindSql(AlumneContacteSearchCriteria $search = null) {
        $sql = 'SELECT * FROM alumnes_contactes WHERE deleted = 0 ';
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
     * @return AlumneContacte
     * @throws Exception
     */
    private function insert(AlumneContacte $alumneContacte) {
        $now = new DateTime();
        $alumneContacte->setId(null);
        $alumneContacte->setDataCreacio($now);
        $alumneContacte->setDataModificacio($now);
        $sql = '
            INSERT INTO `alumnes_contactes` (`id_alumne`, `id_contacte`, `relacio`, `relacio_altres`, `data_creacio`, `data_modificacio`, `esborrat`) 
            VALUES (:id_alumne, :id_contacte, :relacio, :relacio_altres, :data_creacio, :data_modificacio, :esborrat);';
        return $this->execute($sql, $alumneContacte);
    }

    /**
     * @return AlumneContacte
     * @throws Exception
     */
    private function update(AlumneContacte $alumneContacte) {
        $alumneContacte->setDataModificacio(new DateTime());
        $sql = '
            UPDATE `alumnes_contactes` SET
                `id_alumne` = :id_alumne, 
                `id_contacte` = :id_contacte, 
                `relacio` = :relacio, 
                `relacio_altres` = :relacio_altres, 
                `data_modificacio` = :data_modificacio, 
                `esborrat` = :esborrat
            WHERE
                `id` = :id';
        return $this->execute($sql, $alumneContacte);
    }

    /**
     * @return AlumneContacte
     * @throws Exception
     */
    private function execute($sql, AlumneContacte $alumneContacte) {
        $statement = $this->getDb()->prepare($sql);
        $this->executeStatement($statement, $this->getParams($alumneContacte));
        if (!$alumneContacte->getId()) {
            return $this->findById($this->getDb()->lastInsertId());
        }
        if ($statement->errorCode() != 0) {
            throw new Exception("Error al grabar la relació alumne-contacte: ".$statement->errorInfo()) ;
        }
        return $alumneContacte;
    }

    private function getParams(AlumneContacte $alumneContacte) {
        $params = [
            ':id_alumne' => $alumneContacte->getIdAlumne(),
            ':id_contacte' => $alumneContacte->getIdContacte(),
            ':relacio' => $alumneContacte->getRelacio(),
            ':relacio_altres' => $alumneContacte->getRelacioAltres(),
            ':data_creacio' => self::formatDateTime($alumneContacte->getDataCreacio()),
            ':data_modificacio' => self::formatDateTime($alumneContacte->getDataModificacio()),
            ':esborrat' => self::formatBoolean($alumneContacte->getEsborrat())
        ];
        if ($alumneContacte->getId()) {
            // unset data_creacio, this one is never updated
            unset($params[':data_creacio']);
            $params[':id'] = $alumneContacte->getId();
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
