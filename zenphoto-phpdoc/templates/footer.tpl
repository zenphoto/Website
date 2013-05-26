<td width="195" class="menu">
    <div class="package-title">{$package}</div>
{if count($ric) >= 1}
  <div class="package">
	<div id="ric">
		{section name=ric loop=$ric}
			<p><a href="{$subdir}{$ric[ric].file}">{$ric[ric].name}</a></p>
		{/section}
	</div>
	</div>
{/if}
{if $hastodos}
  <div class="package">
	<div id="todolist">
			<p><a href="{$subdir}{$todolink}">Todo List</a></p>
	</div>
	</div>
{/if}
      <b>Packages:</b><br />
  <div class="package">
      {section name=packagelist loop=$packageindex}
        <a href="{$subdir}{$packageindex[packagelist].link}">{$packageindex[packagelist].title}</a><br />
      {/section}
	</div>
      <br />
{if $tutorials}
		<b>Tutorials/Manuals:</b><br />
  <div class="package">
		{if $tutorials.pkg}
			<strong>Package-level:</strong>
			{section name=ext loop=$tutorials.pkg}
				{$tutorials.pkg[ext]}
			{/section}
		{/if}
		{if $tutorials.cls}
			<strong>Class-level:</strong>
			{section name=ext loop=$tutorials.cls}
				{$tutorials.cls[ext]}
			{/section}
		{/if}
		{if $tutorials.proc}
			<strong>Procedural-level:</strong>
			{section name=ext loop=$tutorials.proc}
				{$tutorials.proc[ext]}
			{/section}
	</div>
		{/if}
{/if}
      {if !$noleftindex}{assign var="noleftindex" value=false}{/if}
      {if !$noleftindex}
      {if $compiledfileindex}
      <b>Files:</b>
      {eval var=$compiledfileindex}
      {/if}
      <br />
      {if $compiledinterfaceindex}
      <b>Interfaces:</b><br />
      {eval var=$compiledinterfaceindex}
      {/if}
      {if $compiledclassindex}
      <b>Classes:</b><br />
      {eval var=$compiledclassindex}
      {/if}
      {/if}
    </td>        
        
        
       
      </td></tr></table>
     
    
    </td>
  </tr>
</table>

  <div class="credit">
		    <hr class="separator" />
		    Documentation generated on {$date} by <a href="{$phpdocwebsite}">phpDocumentor {$phpdocversion}</a>
 </div>
 

	<div id="footer"><a name="stay-tuned"></a>
<div class="column-l">
	<div class="infobox">
		<h3>RSS feeds!</h3>
		<ul class="rsslinks">
			<li><a href="http://www.zenphoto.org/index.php?rss-news&amp;withimages">All news</a> (incl. all	below except forum)</li>
			<li><a href="http://www.zenphoto.org/index.php?rss-news&amp;category=news">New releases and announcements</a></li>
			<li><a href="http://www.zenphoto.org/index.php?rss-news&amp;category=user-guide">User	guide</a></li>
			<li><a href="http://www.zenphoto.org/index.php?rss-news&amp;category=extensions">Extensions</a></li>
			<li><a href="http://www.zenphoto.org/index.php?rss&amp;albumtitle=screenshots&amp;folder=screenshots">Screenshots	and screencasts</a></li>
			<li><a href="http://www.zenphoto.org/index.php?rss&amp;albumtitle=themes&amp;folder=theme">Themes</a></li>
			<li><a href="http://www.zenphoto.org/index.php?rss&amp;albumtitle=showcase&amp;albumname=showcase">Showcase</a></li>
			<li><a href="http://www.zenphoto.org/support/rss.php">Forum</a></li>
		</ul>
		<br />
		<h3>Social networks</h3>
		<ul class="sociallinks">
		 	<li class="link_facebook"><a href="https://www.facebook.com/zenphotocms" title="Zenphoto on Facebook">Facebook</a></li>
			<li class="link_googleplus"><a href="http://google.com/+zenphoto" title="Zenphoto on Google+">Google+</a></li>
			<li class="link_twitter"><a href="http://twitter.com/#!/zenphotocms" title="Zenphoto on Twitter">Twitter</a></li>
			<li class="link_youtube"><a href="http://www.youtube.com/user/acrylian" title="Zenphoto on Youtube">Youtube</a></li>
			<li class="link_github"><a href="https://github.com/zenphoto/zenphoto" title="Zenphoto on GitHub">GitHub</a></li>
		 </ul>
  </div>
</div>

<div class="column-m">
	<div class="infobox">
		<h3>Join the Google Groups!</h3>
			<ul class="downloadlinks">
				<li><a href="http://groups.google.com/group/zenphoto-announce">zenphoto-announce</a><br />Release announcements and security alerts only (no support) 
				<br /><strong>Subscribe right here:</strong><br />
				
				<form	action="http://groups.google.com/group/zenphoto-announce/boxsubscribe" id="googlegroups">
				<small>Email:</small> 
				<input type="text" name="email" size="20"><br />
				<input type="submit" name="sub" value="Subscribe">
				</form><br />
				</li>
				<li><a href="http://groups.google.com/group/zenphoto-translate">Zenphoto
				Translate</a><br />Support and discussion for translators only</li>
			</ul>
			
		</div>
</div>

<div class="column-r">
	<div class="infobox">
		<h3>Information</h3>
		<ul class="downloadlinks">
			<li><a href="http://www.zenphoto.org/pages/about-us" title="About us">About us</a></li>
			<li><a href="http://www.zenphoto.org/pages/contact" title="Contact">Contact</a></li>
			<li><a href="http://www.zenphoto.org/pages/get-involved" title="Get involved!">Get involved!</a></li>
	 		<li><a href="http://www.zenphoto.org/pages/paid-support" title="Paid support">Paid support</a></li>
	 		<li><a href="http://www.zenphoto.org/pages/advertise" title="Advertise">Advertise</a></li>
	 		<li><a href="https://github.com/zenphoto/zenphoto/issues?state=open" title="Bugtracker (GitHub)">Bugtracker <small>(GitHub)</small></li>
	 	</ul>
	 	<br />
	 	<h3>Legal stuff</h3>
		<ul class="downloadlinks">
			<li><a href="http://www.zenphoto.org/pages/licenses" title="licenses">Licenses</a></li>
			<li><a href="http://www.zenphoto.org/support/?terms-of-service=display">Forum rules & Terms of service</a></li>
			<li><a href="http://www.zenphoto.org/pages/privacy" title="privacy">Privacy</a></li>
			<li><a href="http://www.zenphoto.org/pages/contributor-profile-page-information" title="privacy">Contributor profile pages</a></li>

		</ul>
		<p>
		Website powered by Zenphoto<br />
		Forum powered by <a href="http://bbpress.org">bbpress</a>
		</p>
		
	 </div>
</div>
<br style="clear:both" />
</div>

</body>
</html>