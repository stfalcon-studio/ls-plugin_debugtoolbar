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

/**
 * Форматирование размера в байтах
 *
 * @param int $iSize
 * @param string $sFormat - %01.2f %s
 * @return string
 */
function formatBytes($iSize, $sFormat = NULL) {

    // Format string
    $sFormat = ($sFormat === NULL) ? '%01.2f %s' : (string) $sFormat;

    $units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB');

    $iUnit = ($iSize > 0) ? floor(log($iSize, 1024)) : 0;

    return sprintf($sFormat, $iSize / pow(1024, $iUnit), $units[$iUnit]);
}

