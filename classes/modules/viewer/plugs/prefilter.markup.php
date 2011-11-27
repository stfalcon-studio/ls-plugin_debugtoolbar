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
	$oConfig = $oTemplate->getTemplateVars('oConfig');
	$sTplFullpath = str_replace($oConfig->Get('path.root.server'), '', $oTemplate->getTemplateFilepath());
	$sTplFullpath = htmlspecialchars($sTplFullpath, ENT_QUOTES, 'UTF-8');
	return preg_replace('!(<\w+)([^<]*>)!', "$1 tpl=\"{$sTplFullpath}\"$2", $sSource, 1);
}

?>
