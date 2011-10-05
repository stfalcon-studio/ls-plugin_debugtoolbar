<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: Debug Toolbar
 * @Plugin Id: debugtoolbar
 * @Plugin URI:
 * @Description: Shows some technical and debug information of Livestreet
 * @Author: stfalcon-studio
 * @Author URI: http://stfalcon.com
 * @LiveStreet Version: 0.5
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * ----------------------------------------------------------------------------
 */

return array(
    // Tab headers
    'dt_col_mysql_header' => 'SQL запросы к БД',
    'dt_col_php_globals_header' => 'Содержимое глобальных переменных PHP',
    'dt_col_smarty_debug_header' => 'Отладочная информация Smarty',
    // Tab descriptions
    'dt_router_data' => 'Данные роутинга',
    'dt_current_lang' => 'Текущий язык',
    'dt_col_mysql_additional' => 'Всего запросов <b>%%total_count%%</b>, из которых <b>%%cache_count%%</b> из кэша. '
    . 'Время выполнения запросов <b>%%total_time%% сек</b>.<br/>Ниже выведены текущие запросы к БД.',
    'dt_col_php_globals_additional' => 'Внимание! Здесь отображены только глобальные переменные, которые содержат какие либо данные',
    // Buttons
    'dt_close_panel' => 'Убрать панель',
);