<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{$title}</title>
	<link rel="stylesheet" type="text/css" href="{$subdir}media/style.css">
	<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'/>
</head>
<body>

<table border="0" cellspacing="0" cellpadding="0" height="48" width="100%">
  <tr>
	<td class="header-top-left"><a href="http://www.zenphoto.org"><img src="{$subdir}media/header.jpg" border="0" alt="phpDocumentor {$phpdocver}" /></a></td>
    <td class="header-top-right">{$package}<br /><div class="header-top-right-subpackage">{$subpackage}</div></td>
  </tr>
  <tr><td colspan="2" class="header-line"><img src="{$subdir}media/empty.png" width="1" height="1" border="0" alt=""  /></td></tr>
  <tr>
    <td colspan="2" class="header-menu">
      {assign var="packagehaselements" value=false}
      {foreach from=$packageindex item=thispackage}
        {if in_array($package, $thispackage)}
          {assign var="packagehaselements" value=true}
        {/if}
      {/foreach}
      {if $packagehaselements}
  		[ <a href="{$subdir}classtrees_{$package}.html" class="menu">class tree: {$package}</a> ]
		[ <a href="{$subdir}elementindex_{$package}.html" class="menu">index: {$package}</a> ]
      {/if}
      [ <a href="{$subdir}elementindex.html" class="menu">all elements</a> ]
    </td>
  </tr>
  <tr><td colspan="2" class="header-line"><img src="{$subdir}media/empty.png" width="1" height="1" border="0" alt=""  /></td></tr>
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr valign="top">
    
    <td>
      <table cellpadding="10" cellspacing="0" width="100%" border="0"><tr><td valign="top">

{if !$hasel}{assign var="hasel" value=false}{/if}
{if $eltype == 'class' && $is_interface}{assign var="eltype" value="interface"}{/if}
{if $hasel}
<h1>{$eltype|capitalize}: {$class_name}</h1>
Source Location: {$source_location}<br /><br />
{/if}