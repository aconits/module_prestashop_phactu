<div id="block-home-actualite" class="block">
    <p class="title_block"><a href="{$link->getModuleLink('phactu', 'actualites')|escape:'htmlall':'UTF-8'}" title="{l s='Voir toutes les actualités' mod='phactu'}">{l s='Actualités' mod='phactu'}</a></p>
    {if isset($actualites) && $nb_actu > 0}
        <div id="module-phactu" class="block_content clearfix">
            <ul id="liste-phactu">
            {foreach from=$actualites item=actualite}
                <li class="block clearfix{if $actualite@first} active{/if}">
                    <p class="title clearfix">
                        <a title="{l s='Voir plus...' mod='phactu'}" href="{$link->getModuleLink('phactu', 'actualites', ['actualite' => $actualite.id_actualite|intval])|escape:'htmlall':'UTF-8'}">
                            {$actualite.title|escape:'html':'UTF-8'}
                        </a>
                        <span class="date">{$actualite.date_creation|date_format:$phactu_date_format}</span>
                    </p>
                    <div class="preview clearfix" {if $actualite@first}style="display:none;"{/if}>
                        {if $nb_carac > 0}
                            {$actualite.short_description|strip_tags|truncate:$nb_carac:'...':true}
                        {/if}
                    </div>
                    <div class="short_description clearfix" {if !$actualite@first}style="display:none;"{/if}>
                        <div class="rte">{$actualite.short_description}</div>
                        <a class="link-detail" title="{l s='Voir plus...' mod='phactu'}" href="{$link->getModuleLink('phactu', 'actualites', ['actualite' => $actualite.id_actualite|intval])|escape:'htmlall':'UTF-8'}">&raquo; {l s='Voir le détail' mod='phactu'}</a>
                    </div>

                    <p class="expand">...</p>
                </li>
            {/foreach}
            </ul>
        </div>
    {else}            
        <p class='warning'>{l s='Aucune actualités disponibles pour le moment.' mod='phactu'}</p>
    {/if}
</div>