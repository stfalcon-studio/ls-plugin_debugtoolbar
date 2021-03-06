<div id="DTB">
    <ul id="DTBTabs">
		<!-- LiveStreet panel -->
        <li class="dtb-panel-ls">
            <a class="dtb-logo" href="#" target="_blank">Live<span>Street</span>v.{$LS_VERSION}</a>
            <div class="dtb-sub">
                <h3><span class="dtb-close">&ndash;</span>О плагине...</h3>
				<ul>
                    <li><a href="#">Настройки</a></li>
                    <li><a href="https://github.com/stfalcon-studio/ls-plugin_debugtoolbar" target="_blank">Проверить обновление</a></li>
                    <li><a href="http://livestreet.ru/blog/addons/9171.html" target="_blank">Обсуждение</a></li>
                    <li><span>О LiveStreet</span></li>
                    <li><a href="http://livestreet.ru" target="_blank">Оффициальный сайт LiveStreet</a></li>
                </ul>
            </div>
        </li>
		<!-- Database panel -->
		<li>
			<a title="{$sMysqlDescription|escape}" href="#"><span class="dtb-ico dtb-ico-database">{$aStats.total.count} ({$aStats.total.time}s)</span></a>
			<div class="dtb-sub dtb-sub-wide">
				<h3><span class="dtb-close"> &ndash; </span>{$aLang.plugin.debugtoolbar.dt_database_header}</h3>
				<div class="dtb-sub-inner">
					<div class="dtb-sub-header">
						<div class="dtb-sub-header-search">
							<label for="dtb-database-keyword">{$aLang.plugin.debugtoolbar.dt_database_search}</label>
							<input class="dtb-input input-300" id="dtb-database-keyword" type="text" value=""/><br />
						</div>
						<div class="dtb-switcher">
							<a class="dtb-ico-time-all active" href="#">{$aLang.plugin.debugtoolbar.dt_database_status_default}</a>
							<a class="dtb-ico-time-fatal" href="#fatal">> 500ms</a>
							<a class="dtb-ico-time-warning" href="#warning">> 100ms</a>
							<a class="dtb-ico-time-look" href="#look">> 50ms</a>
						</div>
						<div class="dtb-clear"></div>

					</div>
					<div class="dtb-sub-content">
						<table id="dtb-database-table">
							<thead>
								<tr>
									<th style="width:24px;">#</th>
									<th style="width:50px;">Время</th>
									<th>Запрос</th>
								</tr>
							</thead>
							<tbody>
								{foreach from=$aSqlDetails key=sKey item=sValue}
									<tr class="dtb-time-{$sValue.rowStyle}">
										<td class="dtb-center">{$sKey}</td>
										<td class="dtb-right">{$sValue.time_text|escape}</td>
										<td class="dtb-td-search"><pre class="sh_sql dtb-pre-wrap">{$sValue.query|escape}</pre></td>
									</tr>
								{/foreach}
							</tbody>
						</table>
					</div>
					<div class="dtb-sub-footer">{$sMysqlDescription}</div>
				</div>
			</div>
		</li>
        <li><span class="dtb-ico dtb-ico-pagetime">{$iTimeFull}s</span></li>
		<li><span  title="{$aLang.plugin.debugtoolbar.dt_ramusage_description}" class="dtb-ico dtb-ico-ramusage">{$aRamUsage.total} (Peak: {$aRamUsage.peak})</span></li>
        <li>
			<a href="#"><span class="dtb-ico dtb-ico-globals">PHP GLOBALS</span></a>
			<div class="dtb-sub dtb-sub-wide">
				<h3><span class="dtb-close">&ndash;</span>PHP GLOBALS</h3>
				<div class="dtb-sub-inner">
					<div class="dtb-sub-content">
						<dl>
							{foreach from=$aPhpGlobals key=sKey item=sValue}
								{if $sValue}
									<dt>{$sKey}</dt>
									<dd><pre>{$sValue|debug_print_var:0:150 }</pre></dd>
								{/if}
							{/foreach}
						</dl>
					</div>
					<div class="dtb-sub-footer">{$aLang.plugin.debugtoolbar.dt_php_globals_description}</div>
				</div>
			</div>
		</li>
		{if $aPanels.smarty}
			<li>
				<a href="#"><span class="dtb-ico dtb-ico-smarty">Smarty Debug</span></a>
				<div class="dtb-sub dtb-sub-wide">
					<h3><span class="dtb-close"> &ndash; </span>{$aLang.plugin.debugtoolbar.dt_smarty_debug_header}</h3>
					<div class="dtb-sub-inner">
						<div class="dtb-sub-content">{debug}</div>
						<div class="dtb-sub-footer">{$aLang.plugin.debugtoolbar.dt_smarty_description}</div>
					</div>
				</div>
			</li>
		{/if}
        <li><span title="{$aLang.plugin.debugtoolbar.dt_router_description}" class="dtb-ico dtb-ico-route">{$sRouter}</span></li>
        <li><span  title="{$aLang.plugin.debugtoolbar.dt_lang_description}" class="dtb-ico dtb-ico-language">{$sCurrentLang}</span></li>
        <li class="dtb-panel-tpl">
			<a href="#"><span class="dtb-ico dtb-ico-templates">{$aLang.plugin.debugtoolbar.dt_used_templates}</span></a>
            <div class="dtb-sub">
                <h3><span class="dtb-close">&ndash;</span>{$aLang.plugin.debugtoolbar.dt_used_templates_header}</h3>
                <ul id="DTBTplList">
                    <li><p class="dtb-center">Не удалось получить сведения о шаблонах.</p></li>
                </ul>
                <div class="dtb-fieldset">
					<label><input type="checkbox" id="DTBSwTplFinder" value="1"/>Включить подсветку шаблонов</label>
				</div>
            </div>
		</li>
        <li class="dtb-hide-toolbar"><span title="{$aLang.plugin.debugtoolbar.dt_hide_toolbar}" class="dtb-close">&nbsp;</span></li>
    </ul>
</div>
<div id="DTBShow" title="{$aLang.plugin.debugtoolbar.dt_show_toolbar}"><span class="dtb-show-toolbar">&nbsp;</span></div>