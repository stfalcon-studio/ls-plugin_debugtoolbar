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
		$sWholeTplFullpath = str_replace($oConfig->Get('path.root.server'), '', $oTemplate->template_resource);
		$sWholeTplFullpath = htmlspecialchars($sWholeTplFullpath, ENT_QUOTES, 'UTF-8');
		return $sSource;
	}
        
	$sPattern = '!(<html)([^<]*>)!';
	if ($sWholeTplFullpath && preg_match($sPattern, $sSource)) {
		$sSource = preg_replace($sPattern, "$1 tpl=\"{$sWholeTplFullpath}\"$2", $sSource, 1);
	}

	$sTplFullpath = str_replace($oConfig->Get('path.root.server'), '', $oTemplate->template_resource);
	$sTplFullpath = htmlspecialchars($sTplFullpath, ENT_QUOTES, 'UTF-8');

	$sPattern = '!(<body)([^<]*>)!';
	if (preg_match($sPattern, $sSource)) {
		return preg_replace($sPattern, "$1 tpl=\"{$sTplFullpath}\"$2", $sSource, 1);
	} else {
		return preg_replace('!(<\w+)([^<]*>)!', "$1 tpl=\"{$sTplFullpath}\"$2", $sSource, 1);
	}
}

?>
