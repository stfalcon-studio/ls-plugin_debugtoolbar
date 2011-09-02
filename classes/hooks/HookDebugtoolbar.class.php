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

class PluginDebugToolbar_HookDebugToolbar extends Hook
{

    public function RegisterHook() {
        /**
         * Хук для вставки HTML кода
         */
        if ($oUserCurrent = $this->User_GetUserCurrent() and $oUserCurrent->isAdministrator()) {
            $this->AddHook('engine_init_complete', 'AddAssets');
            $this->AddHook('template_body_end', 'RenderToolbar');
        }
    }

    /**
     * Add plugin assets to template
     */
    public function AddAssets() {
        $this->Viewer_AppendStyle(Plugin::GetTemplatePath(__CLASS__) . 'css/style.css');
        $this->Viewer_AppendScript(Plugin::GetTemplatePath(__CLASS__) . 'js/jquery.js');
        $this->Viewer_AppendScript(Plugin::GetTemplatePath(__CLASS__) . 'js/debugtoolbar.js');
    }

    /**
     * Вывод панели в шаблон
     *
     * @return type
     */
    public function RenderToolbar() {
        $oEngine = Engine::getInstance();

        $aStats = $oEngine->getStats();

        $aStats['cache']['time'] = round($aStats['cache']['time'], 5);

        $iTimeInit = $oEngine->GetTimeInit();
        $iTimeFull = round(microtime(true) - $iTimeInit, 3);


        $aSqlQueries = PluginDebugToolbar::getSqlData();

        // Зададим стиль для строки вывода запроса
        foreach ($aSqlQueries as $sKey => &$sValue) {
            $iTime = (int) $sValue['time'];

            if ($iTime > 1000) {
                $sRowStyle = 'Fatal';
            } else if ($iTime > 500) {
                $sRowStyle = 'Urgent';
            } else if ($iTime > 100) {
                $sRowStyle = 'Warning';
            } else if ($iTime > 30) {
                $sRowStyle = 'Look';
            } else {
                $sRowStyle = 'Normal';
            }
            $sValue['rowStyle'] = $sRowStyle;
        }
        fb($aSqlQueries);
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('aSqlQueries', $aSqlQueries);
        $this->Viewer_Assign('aStats', $aStats);
        $this->Viewer_Assign('iTimeFull', $iTimeFull);
        $this->Viewer_Assign('LS_VERSION', LS_VERSION);

        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'toolbar.tpl');
    }

}
