<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: Debug Toolbar
 * @Plugin URI:  https://github.com/stfalcon-studio/ls-plugin_debugtoolbar
 * @Author: Web studio stfalcon.com
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * ----------------------------------------------------------------------------
 */

return array(
	/**
	 *  Настройки доступа к панели
	 *  0 - для всех включая гостей
	 *  1 - для зарегистрированных
	 *  2 - только для администраторов
	 */
	'access_level' => 0,
	'template' => array(
		/**
		 * Принудительная компиляция шаблонов
		 * ВНИМАНИЕ! Активное состояние данной опции увеличивает время открытия страницы
		 */
		'force_compile' => 1
	),
	'panels' => array(
		'smarty' => true,
	)
);
