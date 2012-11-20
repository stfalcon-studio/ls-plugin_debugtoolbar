<?php

/**
 * Description of Cache
 *
 * @author verdet
 */
class PluginDebugtoolbar_ModuleCache extends PluginDebugtoolbar_Inherit_ModuleCache
{

    /**
   	 * Get cached value from the cache storage
   	 *
   	 * @param string $sName
     *
   	 * @return mixed
   	 */
    public function Get($sName)
    {
        PluginDebugtoolbar::setCacheData('get', $sName);

        return parent::Get($sName);
    }

    /**
     * Set cached value to the cache storage
     *
     * @param mixed    $data
     * @param string   $sName
     * @param array    $aTags
     * @param bool|int $iTimeLife
     *
     * @return bool
     */
    public function Set($data, $sName, $aTags = array(), $iTimeLife = false)
    {
        PluginDebugtoolbar::setCacheData('set', $sName);

        return parent::Set($data, $sName, $aTags, $iTimeLife);
    }
}