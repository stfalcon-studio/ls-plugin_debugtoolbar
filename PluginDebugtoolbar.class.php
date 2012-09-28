<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: Debug Toolbar
 * @Plugin URI:  https://github.com/stfalcon-studio/ls-plugin_debugtoolbar
 * @Author: Web studio stfalcon.com
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * ----------------------------------------------------------------------------
 */

// Prevents a direct access to the file
if (!class_exists('Plugin')) {
	die('Hacking attemp!');
}

// Подключаем необходимые плагину функции
include_once 'include/dt_functions.php';

class PluginDebugtoolbar extends Plugin
{

	// SQL queries storage
	protected static $aSqlData = array();
//      // Cache query store
	protected static $aCacheData = array();

	/**
	 * Store SQL data into temp storage
	 *
	 * @param object $oDb
	 * @param string $sMessage
	 */
	public static function setSqlData($oDb, $sMessage)
	{
		static $iQueryCount = 0;

		/**
		 * Так как пока не получается нормально получить время исполнения запроса и сам запрос,
		 * будем парсить кашу которая стекается в лог файл.
		 * @TODO: Найти способ нормально получить данные о запросах из DbSimple.
		 */
		if (false !== strpos($sMessage, '--')) {
			// Result data: -- 0 ms; returned 0 row(s)
			if (preg_match("/(\d+).+returned\s+(.*)/u", $sMessage, $aMatch)) {
				self::$aSqlData[$iQueryCount]['time'] = $aMatch[1];
			} else {
				self::$aSqlData[$iQueryCount]['time'] = 0;
			}
		} else {
			// SQL query clean
			$aReplace = array(
				"/\s+/u" => ' ',
				"/(\s+)?([\)\,\=])(\s+)?/u" => '\2 ',
				"/(\()/u" => ' \1',
				"/([=])/u" => ' \1 ',
				"/[;]/u" => '',
			);

			$sMessage = preg_replace(array_keys($aReplace), array_values($aReplace), $sMessage) . ';';

			$iQueryCount++;
			self::$aSqlData[$iQueryCount]['query'] = $sMessage;
		}
	}

        public static function setCacheData($action, $cacheKey)
        {
            self::$aCacheData[] = array(
                'action' => $action,
                'cacheKey' => $cacheKey
            );
        }

        /**
	 * Get SQL data from temp storage
	 *
	 * @return array
	 */
	public static function getSqlData()
	{
		return self::$aSqlData;
	}
        /*
         * Get Cache data from temp store
         */
	public static function getCacheData()
	{
            return self::$aCacheData;
	}

	/**
	 * Конструктор плагина
	 */
	public function __construct()
	{

		// Включаем логирование запросов, для того чтобы их позже вывести в панель
		Engine::getInstance()->Database_GetConnect()->setLogger('PluginDebugtoolbar::setSqlData');

                if (Config::Get('plugin.debugtoolbar.log_cache')) {
                    $this->aInherits['module'][] = 'ModuleCache';
                }
	}

	/**
	 * Plugin activation
	 *
	 * @return boolean
	 */
	public function Activate()
	{
		$this->Cache_Clean();
		$this->Viewer_GetSmartyObject()->clearCompiledTemplate();
		return true;
	}

	/**
	 * Plugin deactivation
	 *
	 * @return boolean
	 */
	public function Deactivate()
	{
		$this->Cache_Clean();
		$this->Viewer_GetSmartyObject()->clearCompiledTemplate();
		return true;
	}

	/**
	 * Plugin initialization
	 */
	public function Init()
	{
		$oSmarty = $this->Viewer_GetSmartyObject();

		// Переопределяем шаблон отладчика Smarty
		$oSmarty->debug_tpl = Plugin::GetTemplatePath(__CLASS__) . 'smarty.debug.tpl';

		// Добавляем директорию Smarty - плагинов
		$oSmarty->addPluginsDir(dirname(__FILE__) . '/classes/modules/viewer/plugs');

		if ((bool) Config::Get('plugin.debugtoolbar.template.force_compile')) {
			// Сбрасываем кэш скомпилированных шаблонов
			$oSmarty->clearCompiledTemplate();
		}

		// Добавляем предварительный фильтр спец. разметки
		$oSmarty->loadFilter('pre', 'markup');
	}

}