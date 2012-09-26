<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: Debug Toolbar
 * @Plugin URI:
 * @Description: Shows the advanced debug information of the LiveStreet
 * @Author: Web studio stfalcon.com
 * @Author URI: http://stfalcon.com
 * @LiveStreet Version: 0.5
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * ----------------------------------------------------------------------------
 */

/**
 * Template of whole page
 */
static $sWholeTplFullpath = '';

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     prefilter.markup.php
 * Type:     prefilter
 * Name:     markup
 * Purpose:  Makes special markup for Debugtoolbar before 
 * 			 template compilation
 * -------------------------------------------------------------
 *
 * @param type $sSource
 * @param Smarty_Internal_Template $oTemplate
 * @return type 
 */

function smarty_prefilter_markup($sSource, Smarty_Internal_Template $oTemplate)
{
	global $sWholeTplFullpath;
	$oConfig = $oTemplate->getTemplateVars('oConfig');
	if ($sWholeTplFullpath == '') {
		$sWholeTplFullpath = get_path_to_file($oTemplate);
		return $sSource;
	}
        
	$sPattern = '!(<html)([^<]*>)!';
	if ($sWholeTplFullpath && preg_match($sPattern, $sSource)) {
		$sSource = preg_replace($sPattern, "$1 tpl=\"{$sWholeTplFullpath}\"$2", $sSource, 1);
	}

	$sTplFullpath = get_path_to_file($oTemplate);

	$sPattern = '!(<body)([^<]*>)!';
	if (preg_match($sPattern, $sSource)) {
		return preg_replace($sPattern, "$1 tpl=\"{$sTplFullpath}\"$2", $sSource, 1);
	} else {
		return preg_replace('!(<\w+)([^<]*>)!', "$1 tpl=\"{$sTplFullpath}\"$2", $sSource, 1);
	}
}

/**
 * Get path to template file depending on LiveStreet version
 * 
 * @param Smarty_Internal_Template $oTemplate
 * @return type
 */
function get_path_to_file(Smarty_Internal_Template $oTemplate)
{
    $oConfig = $oTemplate->getTemplateVars('oConfig');

    if (version_compare(LS_VERSION, '1.0', '>=')) {
        $file = $oTemplate->smarty->_current_file;
    } else if (version_compare(LS_VERSION, '0.5', '>=')) {
        $file = $oTemplate->getTemplateFilepath();
    } else {
        return '';
    }

    $path = str_replace($oConfig->Get('path.root.server'), '', $file);

    return htmlspecialchars($path, ENT_QUOTES, 'UTF-8');
}

?>
