{crmScope extensionKey='civimodels'}
<div class='crm-content-block'>
<h3>{ts}Model data{/ts}</h3>

{* If data variable exists, print out *}
{if isset($models)}
  <table>
  <tbody>
    {foreach name="model" from=$models item="model"}
      {if $smarty.foreach.model.index % 2 == 0}
        <tr>
      {/if}
        <td width="50%">
          {assign var="template" value=$model.template}
          {include file=$template model=$model}
        </td>
      {if $smarty.foreach.model.index % 2 == 1 || $smarty.foreach.model.last}
        </tr>
      {/if}
    {/foreach}
  </tbody>
</table>
{else}
<p>No data to display</p>
{/if}
{/crmScope}
