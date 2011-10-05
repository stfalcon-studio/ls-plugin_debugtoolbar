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

class PluginDebugtoolbar_ModuleViewer extends PluginDebugtoolbar_Inherit_ModuleViewer
{

    /**
     * Инициализация модуля
     *
     */
    public function Init()
    {
        parent::Init();
        // Переопределяем шаблон Smarty Debug Console
        $this->oSmarty->debug_tpl = Plugin::GetTemplatePath(__CLASS__) . 'smarty.debug.tpl';
    }

}
