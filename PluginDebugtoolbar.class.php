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

        // Включаем логирование запросов, для того чтобы их позже вывести в панель
        Engine::getInstance()->Database_GetConnect()->setLogger('PluginDebugToolbar::setSqlData');
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
     * @param object $oDb
     * @param string $sMessage
     */
    public static function setSqlData($oDb, $sMessage) {
        static $iQueryCount = 0;

        /**
         * Так как пока не получается нормально получить время исполнения запроса и сам запрос,
         * будем парсить кашу которая стекается в лог файл.
         * @TODO: Найти способ нормально получить данные о запросах из DbSimple.
         */
        if (false !== strpos($sMessage, '--')) {
            // This is result: -- 0 ms; returned 0 row(s)
            if (preg_match("/(\d+).+returned\s+(.*)/u", $sMessage, $aMatch)) {
                self::$aSqlData[$iQueryCount]['time'] = $aMatch[1];
                self::$aSqlData[$iQueryCount]['return'] = $aMatch[2];
            } else {
                self::$aSqlData[$iQueryCount]['time'] = 0;
                self::$aSqlData[$iQueryCount]['return'] = '';
            }
        } else {
            // This is query. So let's clean it to pretty view

            $aReplace = array(
                "#\s+#u" => ' ',
                '#[,.]#u' => '\1 '
            );

            $sMessage = preg_replace(array_keys($aReplace), array_values($aReplace), $sMessage);

            $iQueryCount++;
            self::$aSqlData[$iQueryCount]['query'] = trim($sMessage);
        }
    }

    /**
     * Get SQL data from temp storage
     *
     * @return array
     */
    public static function getSqlData() {
        return self::$aSqlData;
    }

}

// Подключаем необходимые плагину функции
 include_once 'include/dt_functions.php';