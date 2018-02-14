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

namespace LAMgmt\Alert;

use \Exception;

/**
 * Class managing alert messages.
 * <p>
 * (Alert messages are positive messages that are displayed exactly once,
 * on the next page; typically after form submitting.)
 */
final class Alert {

    const ALERTS_KEY = '_alerts';

    private static $alerts = null;


    private function __construct() {
    }

    public static function hasAlerts() {
        self::initAlerts();
        return count(self::$alerts) > 0;
    }

    public static function addAlert($type, $message) {
        if (!strlen(trim($message))) {
            throw new Exception('Cannot insert empty alert message.');
        }
        self::initAlerts();
        //self::$alerts[] = $message;
        self::$alerts[] = [
            'type' => $type,
            'message' => $message];
    }

    /**
     * Get alert messages and clear them.
     * @return array alert messages
     */
    public static function getAlerts() {
        self::initAlerts();
        $copy = self::$alerts;
        self::$alerts = [];
        return $copy;
    }

    private static function initAlerts() {
        if (self::$alerts !== null) {
            return;
        }
        if (!array_key_exists(self::ALERTS_KEY, $_SESSION)) {
            $_SESSION[self::ALERTS_KEY] = [];
        }
        self::$alerts = &$_SESSION[self::ALERTS_KEY];
    }

}
