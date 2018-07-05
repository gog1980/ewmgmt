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

namespace LAMgmt;

use \LAMgmt\Exception\NotFoundException;
use \LAMgmt\Alert\Alert;

$GLOBALS['currPage'] = "";

error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
 * Main application class.
 */

final class Index {
    
    const DEFAULT_PAGE = 'home';
    const PAGE_DIR = '../page/';
    const SCRIPT_DIR = '../scripts/';
    const LAYOUT_DIR = '../layout/';

    private static $CLASSES = [
        'LAMgmt\Exception\NotFoundException' => '/../exception/NotFoundException.php',
        'LAMgmt\Util\Utils' => '/../util/Utils.php',
        'LAMgmt\Config\Config' => '/../config/Config.php',
        'LAMgmt\Util\DataTables\SSP' => '/../util/dataTables/ssp.class.php',
        'LAMgmt\Dao\AlumneDataTable' => '/../dao/alumnes/AlumneDataTable.php',
        'LAMgmt\Dao\AlumneDao' => '/../dao/alumnes/AlumneDao.php',
        'LAMgmt\Dao\ContacteDao' => '/../dao/contactes/ContacteDao.php',
        'LAMgmt\Dao\AlumneContacteDao' => '/../dao/relacions/AlumneContacteDao.php',
        'LAMgmt\Mapping\AlumneMapper' => '/../mapping/AlumneMapper.php',
        'LAMgmt\Mapping\ContacteMapper' => '/../mapping/ContacteMapper.php',
        'LAMgmt\Mapping\AlumneContacteMapper' => '/../mapping/AlumneContacteMapper.php',
        'LAMgmt\Model\Alumne' => '/../model/Alumne.php',
        'LAMgmt\Model\Contacte' => '/../model/Contacte.php',
        'LAMgmt\Model\AlumneContacte' => '/../model/AlumneContacte.php',
        'LAMgmt\Util\UtilsAlumne' => '/../util/UtilsAlumne.php',
        'LAMgmt\Dao\AlumneSearchCriteria' => '/../dao/AlumneSearchCriteria.php',
        'LAMgmt\Dao\ContacteSearchCriteria' => '/../dao/ContacteSearchCriteria.php',
        'LAMgmt\Alert\Alert' => '/../alert/Alert.php',
        /*'TodoList\Validation\TodoValidator' => '/../validation/TodoValidator.php',
        'TodoList\Validation\ValidationError' => '/../validation/ValidationError.php',
        */
    ];
    
    private static $FORBIDENGETPAGES = ['500'];


    /**
     * System config.
     */
    public function init() {
        // error reporting - all errors for development (ensure you have display_errors = On in your php.ini file)
        error_reporting(E_ALL | E_STRICT);
        mb_internal_encoding('UTF-8');
        //mb_http_output('UTF-8');        
        set_exception_handler([$this, 'handleException']);
        spl_autoload_register([$this, 'loadClass']);
        // session
        session_start();
    }

    /**
     * Run the application!
     */
    public function run() {
        $this->runPage($this->getPage());
    }

    /**
     * Exception handler.
     */
    public function handleException($ex) {
        $extra = ['message' => $ex->getMessage()];
        if ($ex instanceof NotFoundException) {
            header('HTTP/1.0 404 Not Found');
            $GLOBALS['currPage'] = "404";
            $this->runPage('404', $extra);
        } else {
            // TODO log exception
            header('HTTP/1.1 500 Internal Server Error');
            $GLOBALS['currPage'] = "500";
            $this->runPage('500', $extra);
        }
    }

    /**
     * Class loader.
     */
    public function loadClass($name) {
        if (!array_key_exists($name, self::$CLASSES)) {
            die('Class "' . $name . '" not found.');
        }
        require_once __DIR__ . self::$CLASSES[$name];
    }

    private function getPage() {
        $GLOBALS['currPage'] = self::DEFAULT_PAGE;
        if (array_key_exists('page', $_GET)) {
            $GLOBALS['currPage'] = $_GET['page'];
        }         
        return $this->checkPage($GLOBALS['currPage']);
    }

    private function checkPage($page) {
        if (!preg_match('/^[a-z0-9-]+$/i', $page)) {
            // TODO log attempt, redirect attacker, ...
            throw new NotFoundException('Pàgina "' . $page . '" sol·licitada no segura');
        }
        if (!$this->hasScript($page)
                && !$this->hasTemplate($page)) {
            // TODO log attempt, redirect attacker, ...
            throw new NotFoundException('Pàgina "' . $page . '" no trobada');
        }
        if (in_array($page, self::$FORBIDENGETPAGES)) {
            throw new NotFoundException('Pàgina no trobada');
        }
        return $page;
    }

    private function runPage($page, array $extra = []) {
        $run = false;
        if ($this->hasScript($page)) {
            $run = true;
            require $this->getScript($page);
        }
        if ($this->hasTemplate($page)) {
            $run = true;
            // data for main template
            $template = $this->getTemplate($page);
            $alerts = null;
            if (Alert::hasAlerts()) {
                $alerts = Alert::getAlerts();
            }            
            // main template (layout)
            require self::LAYOUT_DIR . 'index.phtml';
        }
        if (!$run) {
            die('Page "' . $page . '" has neither script nor template!');
        }
    }

    private function getScript($page) {
        return self::SCRIPT_DIR . $page . '.php';
    }

    private function getTemplate($page) {
        return self::PAGE_DIR . $page . '.phtml';
    }

    private function hasScript($page) {
        return file_exists($this->getScript($page));
    }

    private function hasTemplate($page) {
        return file_exists($this->getTemplate($page));
    }
}
$index = new \LAMgmt\Index();
$index->init();
// run application!
$index->run();