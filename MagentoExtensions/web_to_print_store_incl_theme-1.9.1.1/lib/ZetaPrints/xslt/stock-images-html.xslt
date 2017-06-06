<xsl:stylesheet version='1.0' xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>
  <xsl:output omit-xml-declaration = "yes" />

  <xsl:include href="common-templates.xslt" />

  <xsl:param name="photothumbnail-url-template" />
  <xsl:param name="photothumbnail-url-height-100-template" />
  <xsl:param name="ajax-loader-image-url" />
  <xsl:param name="user-image-edit-button" />

  <xsl:template match="TemplateDetails">
    <xsl:apply-templates select="Pages" />
  </xsl:template>

  <xsl:template match="Pages">
    <xsl:for-each select="Page">
      <xsl:variable name="page-number" select="position()" />

      <xsl:if test="//Images/Image[@Page=$page-number]">
        <div id="stock-images-page-{$page-number}" class="zetaprints-page-stock-images zp-hidden">
          <xsl:call-template name="stock-images-for-page">
            <xsl:with-param name="page" select="$page-number" />
          </xsl:call-template>
        </div>
      </xsl:if>
    </xsl:for-each>
  </xsl:template>
</xsl:stylesheet>
