<div id="block-home-actualite" class="block">
    <p class="title_block"><a href="{$link->getModuleLink('phactu', 'actualites')|escape:'htmlall':'UTF-8'}" title="{l s='Voir toutes les actualités' mod='phactu'}">{l s='Actualités' mod='phactu'}</a></p>
    {if isset($actualites) && $nb_actu > 0}
        <div id="module-phactu" class="block_content clearfix">
            <ul id="liste-phactu">
            {foreach from=$actualites item=actualite}
                <li class="titre block clearfix titre_phactu">
                    <p class="title clearfix">
                        {$actualite.title|escape:'html':'UTF-8'}
                        <span class="date">{$actualite.date_creation|date_format:$phactu_date_format}</span>
                    </p>
                    <div class="preview clearfix"{if $actualite@first} style="display:none;"{/if}>
                        {if $nb_carac > 0}
                            {$actualite.short_description|strip_tags|truncate:$nb_carac:'...':true}
                        {/if}
                    </div>
                </li>
                <li>
                	<div class="short_description clearfix">
                        <div class="rte">{$actualite.short_description}</div>
                        <a class="link-detail" title="{l s='Voir plus...' mod='phactu'}" href="{$link->getModuleLink('phactu', 'actualites', ['actualite' => $actualite.id_actualite|intval])|escape:'htmlall':'UTF-8'}">&raquo; {l s='Voir le détail' mod='phactu'}</a>
                    </div>
                </li>
            {/foreach}
            </ul>
        </div>
        {if $nb_actu > 1}
        	<script type="text/javascript">
				$("#liste-phactu").accordion({
					header: ".titre",
					animate: 800,
					beforeActivate: function( event, ui ) {
						$(ui.newHeader).children("div.preview").slideUp();
					},
					activate: function( event, ui ) {
						$(ui.oldHeader).children("div.preview").slideDown();
					},
				});
        	</script>
        {/if}
    {else}            
        <p class='warning'>{l s='Aucune actualités disponibles pour le moment.' mod='phactu'}</p>
    {/if}
</div>