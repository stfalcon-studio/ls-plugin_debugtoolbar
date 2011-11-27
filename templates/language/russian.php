<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: Debug Toolbar
 * @Plugin URI:  https://github.com/stfalcon-studio/ls-plugin_debugtoolbar
 * @Author: Web studio stfalcon.com
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * ----------------------------------------------------------------------------
 */

return array(
	// Tab headers
	'dt_database_header' => 'SQL запросы к БД',
	'dt_php_globals_header' => 'Содержимое глобальных переменных PHP',
	'dt_smarty_debug_header' => 'Отладочная информация Smarty',
	'dt_used_templates_header' => 'Используемые шаблоны',
	'dt_used_templates' => 'Шаблоны',
	// Tab descriptions
	'dt_router_description' => 'Данные роутинга',
	'dt_lang_description' => 'Текущий язык',
	'dt_database_description' => 'Всего запросов <b>%%total_count%%</b>, из которых <b>%%cache_count%%</b> из кэша. '
	. 'Время выполнения запросов <b>%%total_time%% сек</b>',
	'dt_database_status_default' => 'Показать все',
	'dt_database_search' => 'Фильтр по тексту',
	'dt_ramusage_description' => 'Объем используемой памяти ОЗУ',
	'dt_php_globals_description' => 'Внимание! Здесь отображены только глобальные переменные, которые содержат какие либо данные',
	// Buttons
	'dt_close_panel' => 'Убрать панель',
);