<xsl:stylesheet version='1.0' xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>
  <xsl:output omit-xml-declaration = "yes" />

  <xsl:param name="zetaprints-api-url" />

  <xsl:template match="TemplateDetails">
    <xsl:apply-templates select="Pages" />
  </xsl:template>

  <xsl:template match="Pages">

    <xsl:if  test="not (count(Page) = 1)">
      <xsl:call-template name="image-tabs-for-pages" />
    </xsl:if>

    <xsl:for-each select="Page">
      <xsl:variable name="page-number" select="position()" />
      <xsl:variable name="page-id" select="concat('page-',$page-number)" />
      <div class="product-essential zetaprints-template-page">
      <form id="{$page-id}">
        <input type="hidden" name="zetaprints-From" value="{$page-number}" />
        <input type="hidden" name="zetaprints-TemplateID" value="{/TemplateDetails/@TemplateID}" />
        <input type="hidden" name="preview" />

        <div class="product-img-box">
          <a href="{concat($zetaprints-api-url,@PreviewImage)}"><img title="Click to view in large size" src="{concat($zetaprints-api-url,@PreviewImage)}" /></a>
        </div>

        <div class="product-shop">
          <h3 class="product-name"><xsl:value-of select="@Name" /></h3>

          <xsl:call-template name="fields-for-page">
            <xsl:with-param name="page" select="$page-number" />
          </xsl:call-template>

          <xsl:call-template name="stock-images-for-page">
            <xsl:with-param name="page" select="$page-number" />
          </xsl:call-template>

          <xsl:call-template name="color-pickers-for-page">
            <xsl:with-param name="page" select="$page-number" />
          </xsl:call-template>
        </div>

        <div class="update-preview">
          <input type="button" value="Update preview" class="update-preview form-button" />
          <span>Another page is being updated ...</span>
        </div>
      </form>
      </div>
      
    </xsl:for-each>

    <div class="save-order product-essential">
      <div class="inner">
        <span>Update all pages to enable:</span>
        <input type="button" value="Add to cart" class="disable save-order form-button" />
      </div>
    </div>
  </xsl:template>

  <xsl:template name="image-tabs-for-pages">
    <div class="product-essential">
      <div class="image-tabs">
        <ul style="width: {count(Page) * 135}px;">
          <xsl:for-each select="Page">
            <xsl:variable name="image-name" select="substring-after(@PreviewImage, '/')" />
            <li title="Click to show page">
              <img rel="{concat('page-', position())}" src="{$zetaprints-api-url}thumb/{substring-before($image-name, '.')}_100x100.{substring-after($image-name, '.')}" />
              <br />
              <span><xsl:value-of select="@Name" /></span>
            </li>
          </xsl:for-each>
        </ul>
      </div>
    </div>
  </xsl:template>

  <xsl:template name="fields-for-page">
    <xsl:param name="page" />

    <xsl:for-each select="//Fields/Field[@Page=$page]">
      <dl>
        <dt>
          <label for="field_{position()}">
            <xsl:value-of select="@FieldName" />
            <xsl:text>:</xsl:text>
          </label>
        </dt>
        <dd>
          <xsl:choose>
            <xsl:when test="@Multiline">
              <textarea id="field_{position()}" name="zetaprints-_{@FieldName}">
                <xsl:if test="string-length(@Hint)!=0">
                  <xsl:attribute name="title"><xsl:value-of select="@Hint" /></xsl:attribute>
                </xsl:if>
                <xsl:text>&#x0A;</xsl:text>
              </textarea>
            </xsl:when>
            <xsl:otherwise>
              <xsl:choose>
                <xsl:when test="count(Value)=0">
                  <input type="text" id="field_{position()}" name="zetaprints-_{@FieldName}" class="input-text">
                    <xsl:if test="@MaxLen">
                      <xsl:attribute name="maxlength"><xsl:value-of select="@MaxLen" /></xsl:attribute>
                    </xsl:if>
                    <xsl:if test="string-length(@Hint)!=0">
                      <xsl:attribute name="title"><xsl:value-of select="@Hint" /></xsl:attribute>
                    </xsl:if>
                  </input>
                </xsl:when>
                <xsl:otherwise>
                  <select id="field_{position()}" name="zetaprints-_{@FieldName}" title="{@Hint}">
                    <xsl:for-each select="Value">
                      <option><xsl:value-of select="." /></option>
                    </xsl:for-each>
                  </select>
                </xsl:otherwise>
              </xsl:choose>
            </xsl:otherwise>
          </xsl:choose>
        </dd>
      </dl>
    </xsl:for-each>
  </xsl:template>

  <xsl:template name="stock-images-for-page">
    <xsl:param name="page" />

    <xsl:if test="//Images/Image/StockImage">
    <dl>
      <dt>
        <label for="stock-images-page-{$page}">
          <xsl:value-of select="//Images/Image[@Page=$page]/@Name" />
          <xsl:text>:</xsl:text>
        </label>
      </dt>
      <dd>
        <select id="stock-images-page-{$page}" class="stock-images-selector" name="{concat('zetaprints-#',//Images/Image[@Page=$page]/@Name)}">
          <xsl:for-each select="//Images/Image[@Page=$page]/StockImage">
            <option value="{@FileID}" title="{concat($zetaprints-api-url,'photothumbs/',concat(substring-before(@Thumb,'.'),'_0x100.',substring-after(@Thumb,'.')))}" />
          </xsl:for-each>
        </select>
      </dd>
    </dl>
    </xsl:if>
  </xsl:template>

  <xsl:template name="color-pickers-for-page">
    <xsl:param name="page" />

    <xsl:if test="//Images/Image/@ColourPicker">
      <dl>
        <dt>
          <label for="stock-images-page-{$page}">Colors</label>
        </dt>
        <dd>
          <ul class="colors-selector">
            <xsl:for-each select="//Images/Image[@Page=$page and @ColourPicker='RGB']">
              <li>
                <input class="color" type="checkbox" id="{concat('color-',position())}" name="{concat('zetaprints-#',@Name)}" checked="1" />
                <div class="color-sample"><label for="{concat('color-',position())}"><xsl:value-of select="@Name"/></label></div>
                <span><xsl:value-of select="@Name"/></span>
              </li>
            </xsl:for-each>
          </ul>
        </dd>
      </dl>
    </xsl:if>
  </xsl:template>
</xsl:stylesheet>
