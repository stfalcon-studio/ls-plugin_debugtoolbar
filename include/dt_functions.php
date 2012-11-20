<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: Debug Toolbar
 * @Plugin URI:  https://github.com/stfalcon-studio/ls-plugin_debugtoolbar
 * @Author: Web studio stfalcon.com
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * ----------------------------------------------------------------------------
 */

/**
 * Форматирование размера в байтах
 *
 * @param int $iSize
 * @param string $sFormat - %01.2f %s
 * @return string
 */
function formatBytes($iSize, $sFormat = NULL)
{

	// Format string
	$sFormat = ($sFormat === NULL) ? '%01.2f %s' : (string) $sFormat;

	$units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB');

	$iUnit = ($iSize > 0) ? floor(log($iSize, 1024)) : 0;

	return sprintf($sFormat, $iSize / pow(1024, $iUnit), $units[$iUnit]);
}