<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: Debug Toolbar
 * @Plugin URI:  https://github.com/stfalcon-studio/ls-plugin_debugtoolbar
 * @Author: Web studio stfalcon.com
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * ----------------------------------------------------------------------------
 */

class PluginDebugtoolbar_HookDebugToolbar extends Hook
{

    public function RegisterHook()
    {
        $oUserCurrent = $this->User_GetUserCurrent();

        // Подключать плагин, только администраторам
        switch ((int) Config::Get('plugin.debugtoolbar.access_level')) {
            case 0:
                // Allow for all
                break;
            case 1:
                // Allow only for registered
                if (!$oUserCurrent)
                    return;
                break;
            default:
                // Allow only for administrators
                if (!$oUserCurrent || (!$oUserCurrent->isAdministrator()))
                    return;
        }

        // Хуки плагина
        $this->AddHook('engine_init_complete', 'AddAssets');
        $this->AddHook('template_body_end', 'RenderToolbar', __CLASS__, -99999);
    }

    /**
     * Add plugin assets to template
     */
    public function AddAssets()
    {
        $sPluginPath = Plugin::GetTemplatePath(__CLASS__);

        $this->Viewer_AppendScript($sPluginPath . 'libs/tiptip/jquery.tipTip.js');
        $this->Viewer_AppendStyle($sPluginPath . 'libs/tiptip/tipTip.css');

        $this->Viewer_AppendStyle($sPluginPath . 'libs/shjs/sh_style.css');
        $this->Viewer_AppendScript($sPluginPath . 'libs/shjs/sh_main.min.js');
        $this->Viewer_AppendScript($sPluginPath . 'libs/shjs/sh_sql.min.js');
        $this->Viewer_AppendScript($sPluginPath . 'libs/shjs/sh_php.min.js');

        $this->Viewer_AppendStyle($sPluginPath . 'css/style.css');
        $this->Viewer_AppendScript($sPluginPath . 'js/debugtoolbar.js');
    }

    /**
     * Вывод панели в шаблон
     *
     * @return type
     */
    public function RenderToolbar()
    {
        $oEngine = Engine::getInstance();

        // Статистика запросов к БД
        $aStats                  = $oEngine->getStats();
        $aStats['cache']['time'] = round($aStats['cache']['time'], 3);

        $aStats['total']['count'] = $aStats['sql']['count'] + $aStats['cache']['count'];

        $aStats['total']['time'] = $aStats['sql']['time'] + $aStats['cache']['time'];

        // Детальная информация о запросах к БД
        $aSqlDetails = PluginDebugtoolbar::getSqlData();

        $sMysqlDescription = $this->Lang_Get('plugin.debugtoolbar.dt_database_description', array(
            'total_count' => $aStats['total']['count'],
            'total_time'  => $aStats['total']['time'],
            'cache_count' => $aStats['cache']['count'],
            'cache_time'  => $aStats['total']['time'],
        ));

        $this->Viewer_Assign('sMysqlDescription', $sMysqlDescription);
        // Зададим стиль для строки вывода запроса
        foreach ($aSqlDetails as $sKey => &$sValue) {
            $iTime = (int) $sValue['time'];

            if ($iTime > 500) {
                $sRowStyle = 'fatal';
            } else if ($iTime > 100) {
                $sRowStyle = 'warning';
            } else if ($iTime > 50) {
                $sRowStyle = 'look';
            } else {
                $sRowStyle = 'normal';
            }
            $sValue['rowStyle']  = $sRowStyle;
            $sValue['time_text'] = ($iTime ? $iTime : '< 1') . ' ms';
        }

        // Время создания страницы
        $iTimeFull = round(microtime(true) - $oEngine->GetTimeInit(), 3);

        // Использование ОЗУ
        $aRamUsage['memory_limit'] = ini_get('memory_limit');
        $aRamUsage['total']        = formatBytes(memory_get_usage());
        $aRamUsage['peak']         = formatBytes(memory_get_peak_usage(true));


        // PHP Globals
        $aPhpGlobals = array(
            '$_REQUEST' => $_REQUEST,
            '$_POST'    => $_POST,
            '$_GET'     => $_GET,
            '$_SESSION' => $_SESSION,
            '$_COOKIE'  => $_COOKIE,
            '$_ENV'     => $_ENV,
            '$_SERVER'  => $_SERVER
        );

        if ($sActionEvent = Router::GetActionEvent()) {
            $sActionEvent = 'index';
        }
        /**
         * Данные роутера
         */
        $sRouter = Router::GetActionClass() . '::' . $sActionEvent . '(' . join(', ', Router::GetParams()) . ')';

        $aCacheData = PluginDebugtoolbar::getCacheData();
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('sRouter', $sRouter);
        $this->Viewer_Assign('sCurrentLang', $this->Lang_GetLang());
        $this->Viewer_Assign('aRamUsage', $aRamUsage);
        $this->Viewer_Assign('aSqlDetails', $aSqlDetails);
        $this->Viewer_Assign('aCacheData', $aCacheData);
        $this->Viewer_Assign('aPhpGlobals', $aPhpGlobals);
        $this->Viewer_Assign('aStats', $aStats);
        $this->Viewer_Assign('iTimeFull', $iTimeFull);
        $this->Viewer_Assign('LS_VERSION', LS_VERSION);
        $this->Viewer_Assign('aPanels', Config::Get('plugin.debugtoolbar.panels'));

        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'toolbar.tpl');
    }
}