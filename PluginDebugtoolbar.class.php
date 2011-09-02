<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: Debug Toolbar
 * @Plugin Id: debugtoolbar
 * @Plugin URI:
 * @Description: Shows some technical and debug information of Livestreet
 * @Author: stfalcon-studio
 * @Author URI: http://stfalcon.com
 * @LiveStreet Version: 0.4.2
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * ----------------------------------------------------------------------------
 */

// Prevents a direct access to the file
if (!class_exists('Plugin')) {
    die('Hacking attemp!');
}

class PluginDebugToolbar extends Plugin
{

    public function __construct() {

        parent::__construct();

        $oEngine = Engine::getInstance();

        // Включаем логирование запросов, для того чтобы их позже вывести в панель
        $oDbSimple = $oEngine->Database_GetConnect();

        $oDbSimple->setLogger('PluginDebugToolbar::setSqlData');
    }

    // SQL queries storage
    protected static $aSqlData = array();

    /**
     * Plugin activation
     *
     * @return boolean
     */
    public function Activate() {
        return true;
    }

    /**
     * Plugin initialization
     */
    public function Init() {

    }

    /**
     * Store SQL data into temp storage
     *
     * @staticvar int $count
     * @param type $db
     * @param type $sql
     */
    public static function setSqlData($db, $sql) {
        static $count = 0;
        if (false !== strpos($sql, '--')) {
            // -- 0 ms; returned 0 row(s)
            if (preg_match("/(\d+).*(\d+).*/si", $sql, $aMatch)) {
                self::$aSqlData[$count]['time'] = $aMatch[1];
                self::$aSqlData[$count]['rows'] = $aMatch[2];
            } else {
                self::$aSqlData[$count]['time'] = 0;
                self::$aSqlData[$count]['rows'] = 0;
            }
        } else {
            $count++;
            $aReplace = array(
                "#\s+#u" => ' ',
                '#[,.]#u' => '\1 '
            );
            self::$aSqlData[$count]['query'] = trim(preg_replace(array_keys($aReplace), array_values($aReplace), $sql));
        }
    }

    /**
     * Get SQL data from temp storage
     *
     * @return type
     */
    public static function getSqlData() {
        return self::$aSqlData;
    }

}