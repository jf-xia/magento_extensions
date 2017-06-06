<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="xml"/>

  <xsl:template name="fields-for-page">
    <xsl:param name="page" />

    <xsl:for-each select="//Fields/Field[@Page=$page]">
      <dl>
        <dt>
          <label for="page-{$page}-field-{position()}">
            <xsl:call-template name="trans">
              <xsl:with-param name="key">
                <xsl:value-of select="@FieldName" />
              </xsl:with-param>
            </xsl:call-template>
            <xsl:text>:</xsl:text>
          </label>
        </dt>
        <dd>
          <xsl:choose>
            <xsl:when test="count(Value)=2 and string-length(Value[last()])=0">
              <input type="hidden" name="zetaprints-_{@FieldName}" value="&#x2E0F;" class="zetaprints-field" />
              <input id="page-{$page}-field-{position()}" type="checkbox" name="zetaprints-_{@FieldName}" value="{Value[1]}" class="zetaprints-field">
                <xsl:if test="@Value=Value[1]">
                  <xsl:attribute name="checked">1</xsl:attribute>
                </xsl:if>
                <xsl:attribute name="title">
                  <xsl:call-template name="trans">
                    <xsl:with-param name="key">
                      <xsl:value-of select="@Hint" />
                    </xsl:with-param>
                  </xsl:call-template>
                </xsl:attribute>
              </input>
            </xsl:when>

            <xsl:when test="count(Value)=0">
              <xsl:choose>
                <xsl:when test="@Multiline">
                  <div class="zetaprints-text-field-wrapper">
                  <textarea id="page-{$page}-field-{position()}" name="zetaprints-_{@FieldName}" rows="3" class="zetaprints-field">
                    <xsl:if test="string-length(@Hint)!=0">
                      <xsl:attribute name="title">
                        <xsl:call-template name="trans">
                          <xsl:with-param name="key">
                            <xsl:value-of select="@Hint" />
                          </xsl:with-param>
                        </xsl:call-template>
                      </xsl:attribute>
                    </xsl:if>

                    <xsl:choose>
                      <xsl:when test="@Value and string-length(@Value)!=0">
                        <xsl:value-of select="@Value" />
                      </xsl:when>
                      <xsl:when test="@Story and @StoryAsDefault">
                        <xsl:if test="@StoryAsDefault=2">
                          <xsl:attribute name="readonly">readonly</xsl:attribute>
                        </xsl:if>

                        <xsl:value-of select="@Story" />
                      </xsl:when>
                      <xsl:otherwise>
                        <xsl:text>&#x0A;</xsl:text>
                      </xsl:otherwise>
                    </xsl:choose>
                  </textarea>
                  </div>
                </xsl:when>

                <xsl:otherwise>
                  <div class="zetaprints-text-field-wrapper">
                  <input type="text" id="page-{$page}-field-{position()}" name="zetaprints-_{@FieldName}" class="input-text zetaprints-field">
                    <xsl:if test="@MaxLen">
                      <xsl:attribute name="maxlength"><xsl:value-of select="@MaxLen" /></xsl:attribute>
                    </xsl:if>
                    <xsl:if test="string-length(@Hint)!=0">
                      <xsl:attribute name="title">
                        <xsl:call-template name="trans">
                          <xsl:with-param name="key">
                            <xsl:value-of select="@Hint" />
                          </xsl:with-param>
                        </xsl:call-template>
                      </xsl:attribute>
                    </xsl:if>

                    <xsl:choose>
                      <xsl:when test="@Value and string-length(@Value)!=0">
                        <xsl:attribute name="value">
                          <xsl:value-of select="@Value" />
                        </xsl:attribute>
                      </xsl:when>
                      <xsl:when test="@Story and @StoryAsDefault">
                        <xsl:if test="@StoryAsDefault=2">
                          <xsl:attribute name="readonly">readonly</xsl:attribute>
                        </xsl:if>

                        <xsl:attribute name="value">
                          <xsl:value-of select="@Story" />
                        </xsl:attribute>
                      </xsl:when>
                    </xsl:choose>
                  </input>
                  </div>
                </xsl:otherwise>
              </xsl:choose>
            </xsl:when>

            <xsl:otherwise>
              <select id="page-{$page}-field-{position()}" name="zetaprints-_{@FieldName}" class="zetaprints-field">
                <xsl:attribute name="title">
                  <xsl:call-template name="trans">
                    <xsl:with-param name="key">
                      <xsl:value-of select="@Hint" />
                    </xsl:with-param>
                  </xsl:call-template>
                </xsl:attribute>

                <xsl:for-each select="Value">
                  <xsl:if test=".!=''">
                    <option>
                      <xsl:if test=".=../@Value">
                        <xsl:attribute name="selected">1</xsl:attribute>
                      </xsl:if>
                      <xsl:call-template name="trans">
                        <xsl:with-param name="key">
                          <xsl:value-of select="." />
                        </xsl:with-param>
                      </xsl:call-template>
                    </option>
                  </xsl:if>
                </xsl:for-each>
              </select>
            </xsl:otherwise>
          </xsl:choose>
        </dd>
      </dl>
    </xsl:for-each>
  </xsl:template>

  <xsl:template name="stock-images-for-page">
    <xsl:param name="page" />

    <xsl:for-each select="//Images/Image[@Page=$page]">
      <div class="zetaprints-images-selector no-value minimized block" title="{@Name}">
        <div class="head block-title">
          <a class="image up-down" href="#"><span>
            <xsl:call-template name="trans">
              <xsl:with-param name="key">Up/Down</xsl:with-param>
            </xsl:call-template>
          </span></a>
          <a class="image collapse-expand" href="#"><span>
            <xsl:call-template name="trans">
              <xsl:with-param name="key">Collapse/Expand</xsl:with-param>
            </xsl:call-template>
          </span></a>
          <div class="icon"><span>
            <xsl:call-template name="trans">
              <xsl:with-param name="key">Title</xsl:with-param>
            </xsl:call-template>:
          </span></div>
          <div class="title">
            <label>
              <xsl:call-template name="trans">
                <xsl:with-param name="key">
                  <xsl:value-of select="@Name" />
                </xsl:with-param>
              </xsl:call-template>
            </label>
          </div>
        </div>
        <div id="page-{$page}-tabs-{position()}" class="selector-content">
          <ul class="tab-buttons">
            <xsl:if test="@AllowUpload='1'">
              <li>
                <div class="icon upload"><span>
                  <xsl:call-template name="trans">
                    <xsl:with-param name="key">Upload</xsl:with-param>
                  </xsl:call-template>
                </span></div>
                <a href="#page-{$page}-tabs-{position()}-1"><span>
                  <xsl:call-template name="trans">
                    <xsl:with-param name="key">Upload</xsl:with-param>
                  </xsl:call-template>
                </span></a>
              </li>
              <li class="hidden">
                <div class="icon user-images"><span>
                  <xsl:call-template name="trans">
                    <xsl:with-param name="key">My images</xsl:with-param>
                  </xsl:call-template>
                </span></div>
                <a href="#page-{$page}-tabs-{position()}-2"><span>
                  <xsl:call-template name="trans">
                    <xsl:with-param name="key">My images</xsl:with-param>
                  </xsl:call-template>
                </span></a>
              </li>
            </xsl:if>
            <xsl:if test="StockImage">
              <li>
                <div class="icon stock-images"><span>
                  <xsl:call-template name="trans">
                    <xsl:with-param name="key">Stock images</xsl:with-param>
                  </xsl:call-template>
                </span></div>
                <a href="#page-{$page}-tabs-{position()}-3"><span>
                  <xsl:call-template name="trans">
                    <xsl:with-param name="key">Stock images</xsl:with-param>
                  </xsl:call-template>
                </span></a>
              </li>
            </xsl:if>
            <xsl:if test="@ColourPicker='RGB'">
              <li>
                <div class="icon color-picker"><span>
                  <xsl:call-template name="trans">
                    <xsl:with-param name="key">Color picker</xsl:with-param>
                  </xsl:call-template>
                </span></div>
                <a href="#page-{$page}-tabs-{position()}-4"><span>
                  <xsl:call-template name="trans">
                    <xsl:with-param name="key">Color picker</xsl:with-param>
                  </xsl:call-template>
                </span></a>
              </li>
            </xsl:if>
            <li class="last">
              <label class="leave-blank-value">
                <input id="zetaprints-blank-value" type="radio" name="zetaprints-#{@Name}" value="" class="zetaprints-field">
                  <xsl:if test="@Value and string-length(@Value)=0">
                    <xsl:attribute name="checked">1</xsl:attribute>
                  </xsl:if>
                </input>
                <xsl:call-template name="trans">
                  <xsl:with-param name="key">Blank</xsl:with-param>
                </xsl:call-template>
              </label>
              <label class="leave-default-value">
                <input type="radio" name="zetaprints-#{@Name}" value="#" class="zetaprints-field">
                  <xsl:if test="not(@Value) or @Value='#'">
                    <xsl:attribute name="checked">1</xsl:attribute>
                  </xsl:if>
                </input>
                <xsl:call-template name="trans">
                  <xsl:with-param name="key">Default</xsl:with-param>
                </xsl:call-template>
              </label>
            </li>
          </ul>
          <div class="tabs-wrapper">
          <xsl:if test="@AllowUpload='1'">
            <div id="page-{$page}-tabs-{position()}-1" class="tab upload">
              <div class="column">
                <div class="zetaprints-text-field-wrapper">
                  <input type="text" class="input-text file-name" disabled="true" />
                </div>

                <label>
                  <xsl:call-template name="trans">
                    <xsl:with-param name="key">Upload new image from your computer</xsl:with-param>
                  </xsl:call-template>
                </label>
              </div>

              <div class="column">
                <div class="button choose-file"><span>
                  <xsl:call-template name="trans">
                    <xsl:with-param name="key">Choose file</xsl:with-param>
                  </xsl:call-template>
                </span></div>
                <div class="button cancel-upload disabled"><span>
                  <xsl:call-template name="trans">
                    <xsl:with-param name="key">Upload file</xsl:with-param>
                  </xsl:call-template>
                </span></div>
                <img class="ajax-loader" src="{$ajax-loader-image-url}" />
              </div>

              <div class="clear">
                <div class="recommended-size-notice">
                  <div class="zetaprints-icon attention">&#160;</div>
                  <span>
                    <xsl:call-template name="trans">
                      <xsl:with-param name="key">Recommended size:</xsl:with-param>
                    </xsl:call-template>&#160;<xsl:value-of select="@Width" />
                    &#215;
                    <xsl:value-of select="@Height" />&#160;<xsl:call-template name="trans">
                      <xsl:with-param name="key">px</xsl:with-param>
                    </xsl:call-template>
                  </span>
                </div>
              </div>
            </div>
            <div id="page-{$page}-tabs-{position()}-2" class="tab user-images images-scroller">
              <input type="hidden" name="parameter" value="{@Name}" />
              <table><tr>
                <xsl:for-each select="user-image">
                  <td>
                    <input type="radio" name="zetaprints-#{../@Name}" value="{@guid}" class="zetaprints-images zetaprints-field">
                      <xsl:if test="@guid=../@Value">
                        <xsl:attribute name="checked">1</xsl:attribute>
                      </xsl:if>
                    </input>
                    <a class="edit-dialog" href="{@edit-link}" target="_blank" name="{../@Name}" rel="{@guid}">
                      <xsl:attribute name="title">
                        <xsl:call-template name="trans">
                          <xsl:with-param name="key">Click to edit</xsl:with-param>
                        </xsl:call-template>
                      </xsl:attribute>
                      <img src="{@thumbnail}" id="{@guid}"></img>

                      <div class="buttons-row">
                        <div class="button delete" rel="{@guid}">
                          <xsl:attribute name="title">
                            <xsl:call-template name="trans">
                              <xsl:with-param name="key">Click to delete</xsl:with-param>
                            </xsl:call-template>
                          </xsl:attribute>

                          <xsl:call-template name="trans">
                            <xsl:with-param name="key">Delete</xsl:with-param>
                          </xsl:call-template>
                        </div>

                        <div class="button edit" rel="{@guid}">
                          <xsl:attribute name="title">
                            <xsl:call-template name="trans">
                              <xsl:with-param name="key">Click to edit</xsl:with-param>
                            </xsl:call-template>
                          </xsl:attribute>

                          <xsl:call-template name="trans">
                            <xsl:with-param name="key">Edit</xsl:with-param>
                          </xsl:call-template>
                        </div>
                      </div>
                    </a>
                  </td>
                </xsl:for-each>
              </tr></table>
            </div>
          </xsl:if>
          <xsl:if test="StockImage">
            <div id="page-{$page}-tabs-{position()}-3" class="tab images-scroller">
              <table><tr>
                <xsl:for-each select="StockImage">
                  <td>
                    <input type="radio" name="zetaprints-#{../@Name}" value="{@FileID}" class="zetaprints-field">
                      <xsl:if test="@FileID=../@Value">
                        <xsl:attribute name="checked">1</xsl:attribute>
                      </xsl:if>
                    </input>
                    <a class="in-dialog" target="_blank" rel="group-{../@Name}">
                      <xsl:attribute name="title">
                        <xsl:call-template name="trans">
                          <xsl:with-param name="key">Click to enlarge</xsl:with-param>
                        </xsl:call-template>
                      </xsl:attribute>
                      <xsl:attribute name="href">
                        <xsl:call-template name="produce-url-from-template">
                          <xsl:with-param name="url-template" select="$photothumbnail-url-template" />
                          <xsl:with-param name="filename" select="@Thumb" />
                        </xsl:call-template>
                      </xsl:attribute>
                      <xsl:choose>
                        <xsl:when test="contains(@Thumb, '.png') or contains(@Thumb, '.gif')">
                          <img>
                            <xsl:attribute name="src">
                              <xsl:call-template name="produce-url-from-template">
                                <xsl:with-param name="url-template" select="$photothumbnail-url-template" />
                                <xsl:with-param name="filename" select="@Thumb" />
                              </xsl:call-template>
                            </xsl:attribute>
                          </img>
                        </xsl:when>
                        <xsl:otherwise>
                          <img src="{$zetaprints-api-url}photothumbs/{substring-before(@Thumb,'.')}_0x100.{substring-after(@Thumb,'.')}">
                            <xsl:attribute name="src">
                              <xsl:call-template name="produce-url-from-template">
                                <xsl:with-param name="url-template" select="$photothumbnail-url-height-100-template" />
                                <xsl:with-param name="filename" select="@Thumb" />
                              </xsl:call-template>
                            </xsl:attribute>
                          </img>
                        </xsl:otherwise>
                      </xsl:choose>
                    </a>
                  </td>
                </xsl:for-each>
              </tr></table>
            </div>
          </xsl:if>

          <xsl:if test="@ColourPicker='RGB'">
            <div id="page-{$page}-tabs-{position()}-4" class="tab color-picker">
              <input type="radio" name="zetaprints-#{@Name}" class="zetaprints-field">
                <xsl:choose>
                  <xsl:when test="string-length(@Value)=7 and starts-with(@Value,'#')">
                    <xsl:attribute name="value"><xsl:value-of select="@Value" /></xsl:attribute>
                    <xsl:attribute name="checked">1</xsl:attribute>
                  </xsl:when>
                  <xsl:otherwise>
                    <xsl:attribute name="value"></xsl:attribute>
                    <xsl:attribute name="disabled">1</xsl:attribute>
                  </xsl:otherwise>
                </xsl:choose>
              </input>
              <div class="color-sample">&#160;</div>
              <span>
                <a href="#">
                  <xsl:call-template name="trans">
                    <xsl:with-param name="key">Choose a color</xsl:with-param>
                  </xsl:call-template>
                </a>
                <xsl:call-template name="trans">
                  <xsl:with-param name="key"> and click Select to fill the place of the photo.</xsl:with-param>
                </xsl:call-template>
              </span>
            </div>
          </xsl:if>

          <!-- div block needs content even if there're no options
               for the image field -->
          &#160;

          </div>

          <div class="clear">&#160;</div>
        </div>
      </div>
    </xsl:for-each>
  </xsl:template>

  <xsl:template name="image-tabs-for-pages">
    <div class="zetaprints-image-tabs">
      <ul style="width: {count(Page) * 135}px;">
      <xsl:for-each select="Page">
          <li>
            <xsl:attribute name="title">
              <xsl:call-template name="trans">
                <xsl:with-param name="key">Click to show page</xsl:with-param>
              </xsl:call-template>
            </xsl:attribute>
            <img rel="page-{position()}" src="{@ThumbImageUpdated}">
              <xsl:choose>
                <xsl:when test="@ThumbImageUpdated">
                  <xsl:attribute name="src">
                    <xsl:value-of select="@ThumbImageUpdated" />
                  </xsl:attribute>
                </xsl:when>
                <xsl:otherwise>
                  <xsl:attribute name="src">
                    <xsl:value-of select="@ThumbImage" />
                  </xsl:attribute>
                </xsl:otherwise>
              </xsl:choose>
            </img>
            <br />
            <span>
              <xsl:call-template name="trans">
                <xsl:with-param name="key">
                  <xsl:value-of select="@Name" />
                </xsl:with-param>
              </xsl:call-template>
            </span>
          </li>
        </xsl:for-each>
      </ul>
    </div>
  </xsl:template>

  <xsl:template name="page-size">
    <table>
      <tbody>
        <tr class="page-size-table-head">
          <td class="page-size-table-icon" rowspan="2">
            <img src="{$page-size-icon}" />
          </td>

          <xsl:if test="@GeneratePdf=1">
            <td>PDF</td>
          </xsl:if>

          <xsl:if test="@GenerateJpg=1">
            <td>JPEG</td>
          </xsl:if>

          <xsl:if test="@GenerateGifPng=1">
            <td>GIF/PNG</td>
          </xsl:if>
        </tr>

        <xsl:for-each select="//Pages/Page">
          <tr id="page-size-page-{position()}" class="page-size-table-body zp-hidden">
            <xsl:if test="../../@GeneratePdf=1">
              <td>
                <xsl:choose>
                  <xsl:when test="$page-size-units='in'">
                    <xsl:value-of select="format-number(@WidthIn, '0.00')" />
                    &#215;
                    <xsl:value-of select="format-number(@HeightIn, '0.00')" />&#160;<xsl:call-template name="trans">
                      <xsl:with-param name="key">in</xsl:with-param>
                    </xsl:call-template>
                  </xsl:when>
                  <xsl:when test="$page-size-units='cm'">
                    <xsl:value-of select="format-number(@WidthCm, '0.00')" />
                    &#215;
                    <xsl:value-of select="format-number(@HeightCm, '0.00')" />&#160;<xsl:call-template name="trans">
                      <xsl:with-param name="key">cm</xsl:with-param>
                    </xsl:call-template>
                  </xsl:when>
                </xsl:choose>
              </td>
            </xsl:if>

            <xsl:if test="../../@GenerateJpg=1">
              <td>
                <xsl:choose>
                  <xsl:when test="$page-size-units='in'">
                    <xsl:value-of select="format-number(@WidthIn, '0.00')" />
                    &#215;
                    <xsl:value-of select="format-number(@HeightIn, '0.00')" />&#160;<xsl:call-template name="trans">
                      <xsl:with-param name="key">in</xsl:with-param>
                    </xsl:call-template>
                  </xsl:when>
                  <xsl:when test="$page-size-units='cm'">
                    <xsl:value-of select="format-number(@WidthCm, '0.00')" />
                    &#215;
                    <xsl:value-of select="format-number(@HeightCm, '0.00')" />&#160;<xsl:call-template name="trans">
                      <xsl:with-param name="key">cm</xsl:with-param>
                    </xsl:call-template>
                  </xsl:when>
                </xsl:choose>
              </td>
            </xsl:if>

            <xsl:if test="../../@GenerateGifPng=1">
              <td>
                <xsl:choose>
                  <xsl:when test="$page-size-units='in'">
                    <xsl:value-of select="format-number(@WidthIn, '0.00')" />
                    &#215;
                    <xsl:value-of select="format-number(@HeightIn, '0.00')" />&#160;<xsl:call-template name="trans">
                      <xsl:with-param name="key">in</xsl:with-param>
                    </xsl:call-template>
                  </xsl:when>
                  <xsl:when test="$page-size-units='cm'">
                    <xsl:value-of select="format-number(@WidthCm, '0.00')" />
                    &#215;
                    <xsl:value-of select="format-number(@HeightCm, '0.00')" />&#160;<xsl:call-template name="trans">
                      <xsl:with-param name="key">cm</xsl:with-param>
                    </xsl:call-template>
                  </xsl:when>
                </xsl:choose>
              </td>
            </xsl:if>
          </tr>
        </xsl:for-each>
      </tbody>
    </table>
  </xsl:template>

  <!--The translation template-->
  <xsl:template name="trans">
    <!--Key to search for-->
    <xsl:param name="key"/>
    <xsl:variable name="value">
      <!--Collect matching values - should be one only, if any. Select them from the chunk of the input XML where the translations are stored.-->
      <xsl:for-each select="/TemplateDetails/trans/phrase">
        <xsl:if test="@key=$key">
        <!--There is match on the key - grab the value (presume it's in that attibute, but use your own path)-->
          <xsl:value-of select="@value"/>
        </xsl:if>
      </xsl:for-each>
    </xsl:variable>
    <!--Return either the value of the key if there is no value-->
    <xsl:choose>
      <xsl:when test="$value!=''">
        <xsl:value-of select="$value"/>
      </xsl:when>
      <xsl:otherwise>
        <xsl:value-of select="$key"/>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

  <xsl:template name="produce-url-from-template">
    <xsl:param name='url-template' />
    <xsl:param name='filename' />

    <xsl:variable name="guid" select="substring-before($filename, '.')" />
    <xsl:variable name="ext" select="substring-after($filename, '.')" />

    <xsl:variable name="first-part" select="substring-before($url-template, 'image-guid')" />
    <xsl:variable name="middle-part" select="substring-before(substring-after($url-template, 'image-guid'), 'image-ext')" />
    <xsl:variable name="last-part" select="substring-after($url-template, 'image-ext')" />

    <xsl:value-of select="concat($first-part, $guid, $middle-part, $ext, $last-part)" />
  </xsl:template>
</xsl:stylesheet>
