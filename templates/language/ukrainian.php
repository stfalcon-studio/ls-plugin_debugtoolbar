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
    'dt_col_mysql_header' => 'SQL запити до БД',
    'dt_col_php_globals_header' => 'Вміст глобальних змінних PHP',
    'dt_col_smarty_debug_header' => 'Відлагоджувальна інформація Smarty',
    // Tab descriptions
    'dt_router_data' => 'Дані роутингу',
    'dt_current_lang' => 'Поточна мова',
    'dt_col_mysql_additional' => 'Всього запитів <b>%%total_count%%</b>, з котрих <b>%%cache_count%%</b> з кешу. '
    . 'Час виконання запитів <b>%%total_time%% сек</b>.<br/>Нижче відображено поточні запити до БД.',
    'dt_col_php_globals_additional' => 'Увага! Тут відображені лише глобальні змінні, що містять дані',
    // Buttons
    'dt_close_panel' => 'Прибрати панель',
);