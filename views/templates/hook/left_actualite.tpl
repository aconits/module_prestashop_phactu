<div id="block-left-actualite" class="block">
    <p class="title_block"><a href="{$link->getModuleLink('phactu', 'actualites')|escape:'htmlall':'UTF-8'}" title="{l s='Voir toutes les actualités' mod='phactu'}">{l s='Actualités' mod='phactu'}</a></p>
    {if isset($actualites) && $nb_actu > 0}
        <div id="module-left-phactu" class="block_content clearfix">
            <ul id="liste-left-phactu">
            {foreach from=$actualites item=actualite}
                <li class="block clearfix{if $actualite@first} active{/if}">
                    <p class="title clearfix">
                        <a title="{l s='Voir plus...' mod='phactu'}" href="{$link->getModuleLink('phactu', 'actualites', ['actualite' => $actualite.id_actualite|intval])|escape:'htmlall':'UTF-8'}">
                            {$actualite.title|escape:'html':'UTF-8'}
                        </a>
                    </p>
                    <span class="date">{$actualite.date_creation|date_format:$phactu_date_format}</span>
                    <div class="short_description rte clearfix">
                            {$actualite.short_description|strip_tags|truncate:$nb_carac:'...':true}
                    </div>
                    <a class="link-detail" title="{l s='Voir plus...' mod='phactu'}" href="{$link->getModuleLink('phactu', 'actualites', ['actualite' => $actualite.id_actualite|intval])|escape:'htmlall':'UTF-8'}">&raquo; {l s='Voir le détail' mod='phactu'}</a>
                </li>
            {/foreach}
            </ul>
        </div>
        <script type="text/javascript">
            phactu_speed = {$phactu_speed|intval};
            phactu_execution_time = {$phactu_execution|intval};
        </script>
    {else}            
        <p class='warning'>{l s='Aucune actualités disponibles pour le moment.' mod='phactu'}</p>
    {/if}
</div>