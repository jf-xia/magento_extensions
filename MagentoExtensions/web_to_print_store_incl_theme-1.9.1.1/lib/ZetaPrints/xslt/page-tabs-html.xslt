<xsl:stylesheet version='1.0' xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>

  <xsl:output omit-xml-declaration = "yes" />

  <xsl:include href="common-templates.xslt" />

  <xsl:param name="thumbnail-url-template" />

  <xsl:template match="TemplateDetails">
    <xsl:apply-templates select="Pages" />
  </xsl:template>

  <xsl:template match="Pages">
    <xsl:if  test="not (count(Page) = 1)">
      <xsl:call-template name="image-tabs-for-pages" />
    </xsl:if>
  </xsl:template>
</xsl:stylesheet>
