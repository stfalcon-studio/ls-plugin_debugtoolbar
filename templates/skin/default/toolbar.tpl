<div id="DebugToolbar">
    <div class="dt-logo"><a href="http://livestreet.ru" target="_blank">Live<span>Street</span>v.{$LS_VERSION}</a></div>
    <ul class="dt-panel-items">
        <li class="dt-item-mysql"><a href="#Mysql">{$aStats.total.count} ({$aStats.total.time}s)</a></li>
        <li class="dt-item-pagetime">{$iTimeFull}s</li>
        <li class="dt-item-ramusage">{$aRamUsage.total} (Peak: {$aRamUsage.peak})</li>
        <li class="dt-item-globals"><a href="#PhpGlobals">PHP GLOBALS</a></li>
    </ul>
    <!-- MySQL details information-->
    <div id="dtItemMysql" class="dt-details" style="display: none;">
        <div class="dt-details-header">
            <h2>{$aLang.dt_col_mysql_header}</h2>
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
    <div id="dtItemPhpGlobals" class="dt-details" style="display: none;">
        <div class="dt-details-header">
            <h2>{$aLang.dt_col_php_globals_header}</h2>
            <p class="dt-description">{$aAdditionalInfo.php_globals}</p>
        </div>
        <div class="dt-table-container">
            <dl>
                {foreach from=$aPhpGlobals key=sKey item=sValue}
                    {if $sValue}
                        <dt>{$sKey}</dt> 
                        <dd><pre>{php}print_r($this->get_template_vars('sValue')){/php}</pre></dd>
                    {/if}
                {/foreach}
            </dl>
        </div>
    </div>
</div>