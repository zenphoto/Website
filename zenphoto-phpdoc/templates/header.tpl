<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{$title} functions documentation</title>
	<link rel="stylesheet" type="text/css" href="{$subdir}media/style.css">
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>
</head>
<body>
<div id="container">
	<div id="banner">
	 	<ul>
	 		<li><a href="http://www.zenphoto.org/trac/report" title="Zenphoto bugtracker">Bugtracker</a></li>
	 		<li><a href="http://www.zenphoto.org/pages/get-involved" title="Get involved!">Get involved!</a></li>
	 		<li><a href="#stay-tuned" title="Get involved!">Stay tuned!</a></li>
	 		<li><a href="http://www.zenphoto.org/pages/paid-support" title="Paid support">Paid support</a></li>
	 		<li><a class="sponsors" href="http://www.zenphoto.org/hosting" title="Sponsors">Hosting</a></li>
	 	</ul>
		<div id="header_logo"> 
			<h1 id="logo" title="ZenPhoto">Zenphoto</h1>
            <p>The <em>simpler</em> media website CMS</p>
		</div>
	</div>
	<div id="mainnav">
	  <ul>
		  <li><a href="http://www.zenphoto.org">Download</a></li>
			<li><a href="http://www.zenphoto.org/news">News</a></li>
	 		<li><a href="http://www.zenphoto.org/demo">Demo</a></li>
	 		<li><a href="http://www.zenphoto.org/screenshots/">Screenshots</a></li>
	 		<li><a href="http://www.zenphoto.org/news/category/user-guide">User guide</a></li>
	 		<li><a href="http://www.zenphoto.org/support/">Forum</a></li>
	 		<li><a href="http://www.zenphoto.org/theme/">Themes</a></li>
	 		<li><a href="http://www.zenphoto.org/news/category/extensions">Extensions</a></li>
	 		<li><a href="http://www.zenphoto.org/showcase/">Showcase</a></li>
		</ul>
	</div>

<table border="0" cellspacing="0" cellpadding="0" height="48" width="100%">
  <tr>
	<td>
	
	</td>
   
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