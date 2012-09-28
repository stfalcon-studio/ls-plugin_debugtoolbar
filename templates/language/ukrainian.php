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
	'dt_database_header' => 'SQL запити до БД',
	'dt_php_globals_header' => 'Вміст глобальних змінних PHP',
	'dt_smarty_debug_header' => 'Відлагоджувальна інформація Smarty',
	'dt_used_templates_header' => 'Використані шаблони для створення цієї сторінки',
	'dt_used_templates' => 'Шаблони',
	// Tab descriptions
	'dt_router_description' => 'Дані роутингу',
	'dt_lang_description' => 'Поточна мова',
	'dt_database_description' => 'Всього запитів <b>%%total_count%%</b>, з котрих <b>%%cache_count%%</b> з кешу. '
	. 'Час виконання запитів <b>%%total_time%% сек</b>',
	'dt_database_status_default' => 'Показати усі',
	'dt_database_search' => 'Фільтр по тексту',
	'dt_ramusage_description' => 'Об\'єм використаної пам\'яті ОЗУ',
	'dt_php_globals_description' => 'Увага! Тут відображені лише глобальні змінні, що містять дані',
	// Buttons
	'dt_close_panel' => 'Прибрати панель',
);