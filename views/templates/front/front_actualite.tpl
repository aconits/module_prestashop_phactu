{if isset($pherrors) && $pherrors}
    {capture name=path}{l s='Actualités' mod='phactu'}{/capture}
    <p class="warning">
            {foreach from=$pherrors item=pherror}
                    {$pherror|strip_tags}
            {/foreach}
    </p>
    <a class="previous_page" href="{$link->getModuleLink('phactu', 'actualites', ['page' => $page|intval])|escape:'htmlall':'UTF-8'}">&lt;&lt; {l s='Retour aux actualités' mod='phactu'}</a>
{elseif isset($actualite) && $actualite}
    {capture name=path}<a href="{$link->getModuleLink('phactu', 'actualites')|escape:'htmlall':'UTF-8'}">{l s='Actualités' mod='phactu'}</a><span class="navigation-pipe"></span><span class="navigation_page">{$actualite->title}</span>{/capture}
    <div id="block-phactu-detail">
            <h1>{$actualite->title|escape:'html':'UTF-8'}</h1>
            <p class="date">{$actualite->date_creation|date_format:$phactu_date_format}</p>
            <div class="rte">{$actualite->description}</div>
            
            <a class="previous_page" href="{$link->getModuleLink('phactu', 'actualites', ['page' => $page|intval])|escape:'htmlall':'UTF-8'}">&lt;&lt; {l s='Retour aux actualités' mod='phactu'}</a>
    </div>
{elseif isset($actualites) && $nbActu > 0}
    {capture name=path}{l s='Actualités' mod='phactu'}{/capture}
    <div id="block-phactu" class="clearfix">
            <h1>{l s='Actualités' mod='phactu'}</h1>
            <ul id="front-liste-phactu" class="block clearfix">
            {foreach from=$actualites item=actualite}
                    <li class="item{if $actualite@first} first_item{/if}{if $actualite@last} last_item{/if}">
                            <p class="title"><a href="{$link->getModuleLink('phactu', 'actualites', ['actualite' => $actualite.id_actualite])|escape:'htmlall':'UTF-8'}" title="{l s='Voir plus...' mod='phactu'}">{$actualite.title|escape:'html':'UTF-8'}</a><span class="date">{$actualite.date_creation|date_format:$phactu_date_format}</span></p>
                            <div class="clearfix">
                                    <div class="rte">
                                        {$actualite.short_description|strip_tags}
                                    </div>
                                    
                                        <a class="link-detail" href="{$link->getModuleLink('phactu', 'actualites', ['actualite' => $actualite.id_actualite, 'page' => $page|intval])|escape:'htmlall':'UTF-8'}" title="{l s='Voir plus...' mod='phactu'}">&raquo; {l s='Voir le détail' mod='phactu'}</a>
                            </div>
                    </li>
            {/foreach}
            </ul>
            
            {if isset($nbPage) && $nbPage > 1}
                <div id="pagination_bottom" class="pagination clearfix">
                        <ul class="pagination">
                                <li id="pagination_previous" class="pagination_previous{if $page == 1} disabled{/if}">
                                        {if $page == 1}
                                            <span>&laquo; {l s='Précédent' mod='phactu'}</span>
                                        {else}
                                            <a href="{$link->getModuleLink('phactu', 'actualites', ['page' => $prevPage|intval])|escape:'htmlall':'UTF-8'}">&laquo; {l s='Précédent' mod='phactu'}</a>
                                        {/if}
                                </li>
                                {for $p=1 to $nbPage}
                                        {if $p == $page}
                                            <li class="current"><span>{$p|intval}</span></li>
                                        {else}
                                            <li><a href="{$link->getModuleLink('phactu', 'actualites', ['page' => $p|intval])|escape:'htmlall':'UTF-8'}">{$p|intval}</a></li>
                                        {/if}
                                {/for}
                                <li id="pagination_next" class="pagination_next{if $page == $nbPage} disabled{/if}">
                                        {if $page == $nbPage}
                                            <span>{l s='Suivant' mod='phactu'} &raquo;</span>
                                        {else}
                                            <a href="{$link->getModuleLink('phactu', 'actualites', ['page' => $nextPage|intval])|escape:'htmlall':'UTF-8'}">{l s='Suivant' mod='phactu'} &raquo;</a>
                                        {/if}
                                </li>
                        </ul>
                </div>
            {/if}
    </div>
{else}
    {capture name=path}{l s='Actualités' mod='phactu'}{/capture}
    <p class="warning">{l s='Aucune actualités disponibles pour le moment.' mod='phactu'}</p>
    <a class="previous_page" href="{$link->getModuleLink('phactu', 'actualites', ['page' => $page|intval])|escape:'htmlall':'UTF-8'}">&lt;&lt; {l s='Retour aux actualités' mod='phactu'}</a>
{/if}