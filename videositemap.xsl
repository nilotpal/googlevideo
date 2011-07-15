<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0" 
    xmlns:html="http://www.w3.org/TR/REC-html40"
    xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
  <xsl:template match="/">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <title>Video Sitemap</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style type="text/css">
    body{font-family:Arial,sans-serif;font-size:11px;margin:0;padding:0;}
	.labnol{margin:10px;float:left;border-top-width:1px;border-right-width:2px;border-bottom-width:2px;border-left-width:1px;border-top-style:solid;border-right-style:solid;border-bottom-style:solid;border-left-style:solid;border-top-color:#CCC;border-right-color:#CCC;border-bottom-color:#CCC;border-left-color:#CCC;width:900px;height:110px;overflow:hidden;padding-top:0px;padding-right:5px;padding-bottom:2px;padding-left:5px;}
	
	.labnol h1{font-family:Georgia,"Times New Roman",Times,serif;font-size:1.2em;margin-left:150px;padding:0 0 5px;}
	.labnol h1 a{text-decoration:none;color:#333}
	.labnol p{color:#666;margin:0px;padding-top:10px;float:left; padding-left:400px;width:300px;}
	.labnol img{float:left;padding-bottom:5px;padding-left:10px;border:none;margin-top:15px;}
	
	.main_heading{margin:10px;float:left;border-top-width:1px;border-right-width:2px;border-bottom-width:2px;border-left-width:1px;border-top-style:solid;border-right-style:solid;border-bottom-style:solid;border-left-style:solid;border-top-color:#CCC;border-right-color:#CCC;border-bottom-color:#CCC;border-left-color:#CCC;width:900px;height:10%;overflow:hidden;padding-top:2px;padding-right:5px;padding-bottom:10px;padding-left:5px; background:#333333;}

	div.new_div_p
	{
		color:#666;
		margin:0px;
		padding-top:10px;
		float:left;
		padding-left:35px;
		width:380px;
		font-size:11px;
	}
	
	div.new_div h1{
	border: medium none;
    float: left;
	width:200px;
	font-size:11px;
	margin-top:10px;
	height:auto;
   	}
	
	h4{float:left; margin:10px 80px 0 82px; color:#FFFFFF; font-size:12px;}
	
	
	.err_msg{margin:10px;float:left;border-top-width:1px;border-right-width:2px;border-bottom-width:2px;border-left-width:1px;border-top-style:solid;border-right-style:solid;border-bottom-style:solid;border-left-style:solid;border-top-color:#CCC;border-right-color:#CCC;border-bottom-color:#CCC;border-left-color:#CCC;width:900px;height:10%;overflow:hidden;padding-top:2px;padding-right:5px;padding-bottom:10px;padding-left:5px;text-align:center; font-weight:bold;
	}
	
    </style>
    </head>
    <body>
	
	<table width="75%" align="center">
	<tr><td>
	
	<div class="main_heading">
	<h4>Video</h4>
	<h4>Landing Page</h4>
	<h4>Description</h4>
	</div>
	
	<xsl:variable name="k"> <xsl:value-of select="sitemap:urlset/sitemap:dt"/> </xsl:variable>
	<xsl:if test="$k>1">
		<div class="err_msg">Video Link is not Available</div>
	</xsl:if>
	
	<xsl:for-each select="sitemap:urlset/sitemap:url">
      <div class="labnol">
        <xsl:variable name="u"> <xsl:value-of select="sitemap:loc"/> </xsl:variable>
        <xsl:variable name="t"> <xsl:value-of select="video:video/video:thumbnail_loc"/> </xsl:variable>
        <a href="{$u}"><img src="{$t}" width="120" height="80" /></a>
		<div class="new_div">
		
        <h1><a href="{$u}"><xsl:value-of select="video:video/video:title"/></a></h1>
		</div>
		<div class="new_div_p">
        
          <xsl:variable name="d"><xsl:value-of select="video:video/video:description"/> </xsl:variable>
          <xsl:choose>
            <xsl:when test="string-length($d) &lt; 100">
              <xsl:value-of select="$d"/>
            </xsl:when>
            <xsl:otherwise>
              <xsl:value-of select="concat(substring($d,1,100),' ...')"/>
            </xsl:otherwise>
          </xsl:choose>
        
		</div>
      </div>
    </xsl:for-each>
	</td>
	</tr>
	</table>
	
	
	
    </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
