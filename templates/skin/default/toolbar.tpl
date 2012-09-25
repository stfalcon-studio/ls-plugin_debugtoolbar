<div id="DebugToolbar">
    <div class="dt-logo"><a href="http://livestreet.ru" target="_blank">Live<span>Street</span>v.{$LS_VERSION}</a></div>
    <ul id="DebugToolbarMenu">
        <li><a class="ico-database" href="#dt-database">{$aStats.total.count} ({$aStats.total.time}s)</a></li>
        <li><span class="ico-pagetime">{$iTimeFull}s</span></li>
        <li><span class="ico-ramusage">{$aRamUsage.total} (Peak: {$aRamUsage.peak})</span></li>
        <li><a class="ico-globals" href="#dt-phpglob">PHP GLOBALS</a></li>
        <li><a class="ico-smarty" href="#dt-smarty">Smarty Debug</a></li>
        <li><span class="ico-route" title="{$aLang.dt_router_data}">{$sRouter}</span></li>
        <li><span class="ico-language" title="{$aLang.dt_current_lang}">{$sCurrentLang}</span></li>
    </ul>

    <!-- MySQL details information-->
    <div id="dt-database" class="dt-details">
        <div class="dt-details-header">
            <h2>{$aLang.plugin.debugtoolbar.dt_col_mysql_header}</h2>
            <p class="dt-description">{$aAdditionalInfo.mysql}</p>
        </div>
        <div class="dt-table-container">
            <table border="0" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th style="width:32px;">#</th>
                        <th style="width:64px;">Execution time</th>
                        <th>Query</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$aSqlDetails key=sKey item=sValue}
                    <tr class="dt-row-status-{$sValue.rowStyle} dt-row-{cycle values='odd,even'}">
                        <td class="dt-center">{$sKey}</td>
                        <td class="dt-right">{$sValue.time_text}</td>
                        <td><pre class="sh_sql dt-pre-wrap">{$sValue.query}</pre></td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    <!-- PHP global variables information-->
    <div id="dt-phpglob" class="dt-details">
        <div class="dt-details-header">
            <h2>{$aLang.dt_col_php_globals_header}</h2>
            <p class="dt-description">{$aAdditionalInfo.php_globals}</p>
        </div>
        <div class="dt-table-container">
            <dl>
                {foreach from=$aPhpGlobals key=sKey item=sValue}
                {if $sValue}
                <dt>{$sKey}</dt>
                <dd><pre>{$sValue|debug_print_var:0:120 }</pre></dd>
                {/if}
                {/foreach}
            </dl>
        </div>
    </div>
    <!-- Smarty Debug information-->
    <div id="dt-smarty" class="dt-details">
        <div class="dt-details-header">
            <h2>{$aLang.dt_col_smarty_debug_header}</h2>
            <p class="dt-description">{$aAdditionalInfo.smarty_debug}</p>
        </div>
        <div class="dt-table-container">{debug}</div>
    </div>
</div>
<div id="DebugToolbarBtn" title="{$aLang.dt_toggle_toolbar}"></div>

