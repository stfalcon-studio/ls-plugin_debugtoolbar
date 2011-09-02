<div id="DebugToolbarWrapper">
    <div class="logo"><a href="/">Live<span>Street</span><span class="version">{$LS_VERSION}</span></a></div>
    <div class="panelCol extended" id="DebugToolbar-mysql">
        <div class="panelColInner">
            <span>{$aStats.sql.count}/{$aStats.cache.count}</span>
        </div>
        <div class="panelColDetails" style="display: none;">
            <table class="fixedHeader">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Query</th>
                        <th>Time, ms</th>
                        <th>Rows</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$aSqlQueries key=sKey item=sValue}
                        <tr class="queryExecution{$sValue.rowStyle}">
                            <td class="alRight">{$sKey}</td>
                            <td>{$sValue.query}</td>
                            <td class="alRight">{$sValue.time}</td>
                            <td class="alCenter">{$sValue.rows}</td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>