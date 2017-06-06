<xsl:stylesheet version='1.0' xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>
  <xsl:output omit-xml-declaration = "yes" />

  <xsl:include href="common-templates.xslt" />

  <xsl:param name="zetaprints-api-url" />

  <xsl:template match="TemplateDetails">
    <xsl:apply-templates select="Pages" />
  </xsl:template>

  <xsl:template match="Pages">
    <xsl:for-each select="Page">
      <xsl:variable name="page-number" select="position()" />

      <xsl:if test="//Images/Image[@Page=$page-number and @ColourPicker='RGB']">
        <div id="color-pickers-page-{$page-number}" class="zetaprints-page-color-pickers">
          <xsl:call-template name="color-pickers-for-page">
            <xsl:with-param name="page" select="$page-number" />
          </xsl:call-template>
       </div>
      </xsl:if>
    </xsl:for-each>
  </xsl:template>
</xsl:stylesheet>
