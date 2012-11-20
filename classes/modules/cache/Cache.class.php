<?php

/**
 * Description of Cache
 *
 * @author verdet
 */
class PluginDebugtoolbar_ModuleCache extends PluginDebugtoolbar_Inherit_ModuleCache
{

    public function Get($sName)
    {
        PluginDebugtoolbar::setCacheData('get', $sName);

        return parent::Get($sName);
    }

    public function Set($data, $sName, $aTags = array(), $iTimeLife = false)
    {
        PluginDebugtoolbar::setCacheData('set', $sName);

        parent::Set($data, $sName, $aTags, $iTimeLife);
    }

}
