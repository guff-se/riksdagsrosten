

</div> <!-- wrapper -->
</div>
<div id="mentions">
			<div class="w960">
				<div class="social"><iframe src="http://www.facebook.com/plugins/like.php?app_id=196658153744046&amp;href=http://www.facebook.com/riksdagsrosten&amp;send=false&amp;layout=button_count&amp;width=1&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font=lucida+grande&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;" allowTransparency="true"></iframe><a href="https://twitter.com/riksdagsrosten" class="twitter-follow-button" data-show-count="false" data-lang="en" data-show-screen-name="false">Follow @riksdagsrosten</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div>
				<div class="clearer">&nbsp;</div>
				<ul id="tweets">
					<!--<li> 
						<img src="#" />
						<p><a class="author" href="#">Namn:</a> <a href="#">@riksdagsrosten</a></p>
						<div class="clearer">&nbsp;</div>
					</li>-->
				</ul>
				<div class="clearer">&nbsp;</div>
				<div id="site-info">
					<div class="left">
					<div>
						<!--<div id="fb-root"></div><script src="http://connect.facebook.net/sv_SE/all.js#appId=196658153744046&xfbml=1"></script><fb:facepile></fb:facepile>-->
						<iframe src="http://www.facebook.com/plugins/facepile.php?app_id=196658153744046&colorscheme=dark&max_rows=2&size=medium" scrolling="no" frameborder="0" style="border:none;overflow:hidden; width:500px; height:47px;" allowTransparency="true"></iframe>
					</div>
					<br/>Copyright &copy; 2012 <strong>Riksdagsrösten</strong> &mdash; Alla rättigheter förbehålls.
					</div>
					<div class="right">
						<ul>
                                                        <li><a href="/privacypolicy">Sekretesspolicy</a></li>
							<li><a href="/anvandarvillkor">Användarvillkor</a></li>
							<li><a href="/om">Om webbplatsen</a></li>
						</ul>
					 </div>
					<div class="clearer">&nbsp;</div>
				</div>
			</div>

<!-- begin olark code --><script type='text/javascript'>/*{literal}<![CDATA[*/window.olark||(function(i){var e=window,h=document,a=e.location.protocol=="https:"?"https:":"http:",g=i.name,b="load";(function(){e[g]=function(){(c.s=c.s||[]).push(arguments)};var c=e[g]._={},f=i.methods.length; while(f--){(function(j){e[g][j]=function(){e[g]("call",j,arguments)}})(i.methods[f])} c.l=i.loader;c.i=arguments.callee;c.f=setTimeout(function(){if(c.f){(new Image).src=a+"//"+c.l.replace(".js",".png")+"&"+escape(e.location.href)}c.f=null},20000);c.p={0:+new Date};c.P=function(j){c.p[j]=new Date-c.p[0]};function d(){c.P(b);e[g](b)}e.addEventListener?e.addEventListener(b,d,false):e.attachEvent("on"+b,d); (function(){function l(j){j="head";return["<",j,"></",j,"><",z,' onl'+'oad="var d=',B,";d.getElementsByTagName('head')[0].",y,"(d.",A,"('script')).",u,"='",a,"//",c.l,"'",'"',"></",z,">"].join("")}var z="body",s=h[z];if(!s){return setTimeout(arguments.callee,100)}c.P(1);var y="appendChild",A="createElement",u="src",r=h[A]("div"),G=r[y](h[A](g)),D=h[A]("iframe"),B="document",C="domain",q;r.style.display="none";s.insertBefore(r,s.firstChild).id=g;D.frameBorder="0";D.id=g+"-loader";if(/MSIE[ ]+6/.test(navigator.userAgent)){D.src="javascript:false"} D.allowTransparency="true";G[y](D);try{D.contentWindow[B].open()}catch(F){i[C]=h[C];q="javascript:var d="+B+".open();d.domain='"+h.domain+"';";D[u]=q+"void(0);"}try{var H=D.contentWindow[B];H.write(l());H.close()}catch(E){D[u]=q+'d.write("'+l().replace(/"/g,String.fromCharCode(92)+'"')+'");d.close();'}c.P(2)})()})()})({loader:(function(a){return "static.olark.com/jsclient/loader0.js?ts="+(a?a[1]:(+new Date))})(document.cookie.match(/olarkld=([0-9]+)/)),name:"olark",methods:["configure","extend","declare","identify"]});
/* custom configuration goes here (www.olark.com/documentation) */
olark.identify('9965-123-10-6199');/*]]>{/literal}*/</script>
<?if(isset($USER)) {?>
	<script type="text/javascript"> olark('api.chat.updateVisitorNickname', { 
		snippet: "<?="$USER->tilltalsnamn"?> <?="$USER->efternamn"?> (<?="$USER->facebook_id"?>)" 
	}); 
	</script>
<?}?>
<!-- end olark code -->

  </body>
</html>