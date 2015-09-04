<?xml version="1.0" encoding="utf-8"?>
<!-- Depth of 2 -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <enterprise type="compare">
<!--             <customer_code>111</customer_code>
            <site_id>222</site_id>
            <org_id>333</org_id> -->
            <users>               
                <xsl:for-each select="enterprise/departments/department">
                <xsl:variable name="department1"><xsl:value-of select="@name" /></xsl:variable>
                
                <!-- department1 > department2 -->
                <xsl:for-each select="child::department">
                <xsl:variable name="department2"><xsl:value-of select="@name" /></xsl:variable>
                
                <!-- department2 > department3 -->
                <xsl:for-each select="child::department">
                <xsl:variable name="department3"><xsl:value-of select="@name" /></xsl:variable>
                
                <!-- department3 > department4 -->
                <xsl:for-each select="child::department">
                <xsl:variable name="department4"><xsl:value-of select="@name" /></xsl:variable>
                
                <!-- department4 > department5 -->
                <xsl:for-each select="child::department">
                <xsl:variable name="department5"><xsl:value-of select="@name" /></xsl:variable>
                
                <!-- department5 > department6 -->
                <xsl:for-each select="child::department">
                <xsl:variable name="department6"><xsl:value-of select="@name" /></xsl:variable>
                
                <!-- department6 > department7 -->
                <xsl:for-each select="child::department">
                <xsl:variable name="department7"><xsl:value-of select="@name" /></xsl:variable>
                
                <!-- department7 > department8 -->
                
                <!-- department7 > users -->
                <xsl:for-each select="child::user">
                <user>
                    <param name="lastname"><xsl:attribute name="value"><xsl:value-of select="@name" /></xsl:attribute></param>
                    <param name="firstname" value="" />
                    <param name="loginname"><xsl:attribute name="value"><xsl:value-of select="concat(@uid ,'@crediteasetest.cn')" /></xsl:attribute></param>
                    <param name="email"><xsl:attribute name="value"><xsl:variable name="temp" select="concat('temp', @title)"/><xsl:choose><xsl:when test="$temp='temp'"><xsl:value-of select="concat(@uid ,'@crediteasetest.cn')" /></xsl:when><xsl:otherwise><xsl:value-of select="@email" /></xsl:otherwise></xsl:choose></xsl:attribute></param>
                    <param name="account" value="" />                    
                    <param name="open" value="是" />
                    <param name="mobile"><xsl:attribute name="value"></xsl:attribute></param>
                    <param name="department1"><xsl:attribute name="value"><xsl:value-of select="$department1" /></xsl:attribute></param>
                    <param name="department2"><xsl:attribute name="value"><xsl:value-of select="$department2" /></xsl:attribute></param>
                    <param name="department3"><xsl:attribute name="value"><xsl:value-of select="$department3" /></xsl:attribute></param>
                    <param name="department4"><xsl:attribute name="value"><xsl:value-of select="$department4" /></xsl:attribute></param>
                    <param name="department5"><xsl:attribute name="value"><xsl:value-of select="$department5" /></xsl:attribute></param>
                    <param name="department6"><xsl:attribute name="value"><xsl:value-of select="$department6" /></xsl:attribute></param>
                    <param name="department7"><xsl:attribute name="value"><xsl:value-of select="$department7" /></xsl:attribute></param>
                </user>        
                </xsl:for-each>                
                </xsl:for-each>
                
                <!-- department6 > users -->
                <xsl:for-each select="child::user">
                <user>
                    <param name="lastname"><xsl:attribute name="value"><xsl:value-of select="@name" /></xsl:attribute></param>
                    <param name="firstname" value="" />
                    <param name="loginname"><xsl:attribute name="value"><xsl:value-of select="concat(@uid ,'@crediteasetest.cn')" /></xsl:attribute></param>
                    <param name="email"><xsl:attribute name="value"><xsl:variable name="temp" select="concat('temp', @title)"/><xsl:choose><xsl:when test="$temp='temp'"><xsl:value-of select="concat(@uid ,'@crediteasetest.cn')" /></xsl:when><xsl:otherwise><xsl:value-of select="@email" /></xsl:otherwise></xsl:choose></xsl:attribute></param>
                    <param name="account" value="" />                    
                    <param name="open" value="是" />
                    <param name="mobile"><xsl:attribute name="value"></xsl:attribute></param>
                    <param name="department1"><xsl:attribute name="value"><xsl:value-of select="$department1" /></xsl:attribute></param>
                    <param name="department2"><xsl:attribute name="value"><xsl:value-of select="$department2" /></xsl:attribute></param>
                    <param name="department3"><xsl:attribute name="value"><xsl:value-of select="$department3" /></xsl:attribute></param>
                    <param name="department4"><xsl:attribute name="value"><xsl:value-of select="$department4" /></xsl:attribute></param>
                    <param name="department5"><xsl:attribute name="value"><xsl:value-of select="$department5" /></xsl:attribute></param>
                    <param name="department6"><xsl:attribute name="value"><xsl:value-of select="$department6" /></xsl:attribute></param>
                </user>        
                </xsl:for-each>                
                </xsl:for-each>
                
                <!-- department5 > users -->
                <xsl:for-each select="child::user">
                <user>
                    <param name="lastname"><xsl:attribute name="value"><xsl:value-of select="@name" /></xsl:attribute></param>
                    <param name="firstname" value="" />
                    <param name="loginname"><xsl:attribute name="value"><xsl:value-of select="concat(@uid ,'@crediteasetest.cn')" /></xsl:attribute></param>
                    <param name="email"><xsl:attribute name="value"><xsl:variable name="temp" select="concat('temp', @title)"/><xsl:choose><xsl:when test="$temp='temp'"><xsl:value-of select="concat(@uid ,'@crediteasetest.cn')" /></xsl:when><xsl:otherwise><xsl:value-of select="@email" /></xsl:otherwise></xsl:choose></xsl:attribute></param>
                    <param name="account" value="" />                 
                    <param name="open" value="是" />
                    <param name="mobile"><xsl:attribute name="value"></xsl:attribute></param>
                    <param name="department1"><xsl:attribute name="value"><xsl:value-of select="$department1" /></xsl:attribute></param>
                    <param name="department2"><xsl:attribute name="value"><xsl:value-of select="$department2" /></xsl:attribute></param>
                    <param name="department3"><xsl:attribute name="value"><xsl:value-of select="$department3" /></xsl:attribute></param>
                    <param name="department4"><xsl:attribute name="value"><xsl:value-of select="$department4" /></xsl:attribute></param>
                    <param name="department5"><xsl:attribute name="value"><xsl:value-of select="$department5" /></xsl:attribute></param>
                </user>        
                </xsl:for-each>                
                </xsl:for-each>
                
                <!-- department4 > users -->
                <xsl:for-each select="child::user">
                <user>
                    <param name="lastname"><xsl:attribute name="value"><xsl:value-of select="@name" /></xsl:attribute></param>
                    <param name="firstname" value="" />
                    <param name="loginname"><xsl:attribute name="value"><xsl:value-of select="concat(@uid ,'@crediteasetest.cn')" /></xsl:attribute></param>
                    <param name="email"><xsl:attribute name="value"><xsl:variable name="temp" select="concat('temp', @title)"/><xsl:choose><xsl:when test="$temp='temp'"><xsl:value-of select="concat(@uid ,'@crediteasetest.cn')" /></xsl:when><xsl:otherwise><xsl:value-of select="@email" /></xsl:otherwise></xsl:choose></xsl:attribute></param>
                    <param name="account" value="" />                   
                    <param name="open" value="是" />
                    <param name="mobile"><xsl:attribute name="value"></xsl:attribute></param>
                    <param name="department1"><xsl:attribute name="value"><xsl:value-of select="$department1" /></xsl:attribute></param>
                    <param name="department2"><xsl:attribute name="value"><xsl:value-of select="$department2" /></xsl:attribute></param>
                    <param name="department3"><xsl:attribute name="value"><xsl:value-of select="$department3" /></xsl:attribute></param>
                    <param name="department4"><xsl:attribute name="value"><xsl:value-of select="$department4" /></xsl:attribute></param>
                </user>        
                </xsl:for-each>                
                </xsl:for-each>
                
                <!-- department3 > users -->
                <xsl:for-each select="child::user">
                <user>
                    <param name="lastname"><xsl:attribute name="value"><xsl:value-of select="@name" /></xsl:attribute></param>
                    <param name="firstname" value="" />
                    <param name="loginname"><xsl:attribute name="value"><xsl:value-of select="concat(@uid ,'@crediteasetest.cn')" /></xsl:attribute></param>
                    <param name="email"><xsl:attribute name="value"><xsl:variable name="temp" select="concat('temp', @title)"/><xsl:choose><xsl:when test="$temp='temp'"><xsl:value-of select="concat(@uid ,'@crediteasetest.cn')" /></xsl:when><xsl:otherwise><xsl:value-of select="@email" /></xsl:otherwise></xsl:choose></xsl:attribute></param>
                    <param name="account" value="" />                   
                    <param name="open" value="是" />
                    <param name="mobile"><xsl:attribute name="value"></xsl:attribute></param>
                    <param name="department1"><xsl:attribute name="value"><xsl:value-of select="$department1" /></xsl:attribute></param>
                    <param name="department2"><xsl:attribute name="value"><xsl:value-of select="$department2" /></xsl:attribute></param>
                    <param name="department3"><xsl:attribute name="value"><xsl:value-of select="$department3" /></xsl:attribute></param>
                </user>        
                </xsl:for-each>                
                </xsl:for-each>
                
                <!-- department2 > users -->
                <xsl:for-each select="child::user">
                <user>
                    <param name="lastname"><xsl:attribute name="value"><xsl:value-of select="@name" /></xsl:attribute></param>
                    <param name="firstname" value="" />
                    <param name="loginname"><xsl:attribute name="value"><xsl:value-of select="concat(@uid ,'@crediteasetest.cn')" /></xsl:attribute></param>
                    <param name="email"><xsl:attribute name="value"><xsl:variable name="temp" select="concat('temp', @title)"/><xsl:choose><xsl:when test="$temp='temp'"><xsl:value-of select="concat(@uid ,'@crediteasetest.cn')" /></xsl:when><xsl:otherwise><xsl:value-of select="@email" /></xsl:otherwise></xsl:choose></xsl:attribute></param>
                    <param name="account" value="" />                   
                    <param name="open" value="是" />
                    <param name="mobile"><xsl:attribute name="value"></xsl:attribute></param>
                    <param name="department1"><xsl:attribute name="value"><xsl:value-of select="$department1" /></xsl:attribute></param>
                    <param name="department2"><xsl:attribute name="value"><xsl:value-of select="$department2" /></xsl:attribute></param>
                </user>
                </xsl:for-each>
                </xsl:for-each>
                
                <!-- department1 > users -->
                <xsl:for-each select="child::user">
                <user>
                    <param name="lastname"><xsl:attribute name="value"><xsl:value-of select="@name" /></xsl:attribute></param>
                    <param name="firstname" value="" />
                    <param name="loginname"><xsl:attribute name="value"><xsl:value-of select="concat(@uid ,'@crediteasetest.cn')" /></xsl:attribute></param>
                    <param name="email"><xsl:attribute name="value"><xsl:variable name="temp" select="concat('temp', @title)"/><xsl:choose><xsl:when test="$temp='temp'"><xsl:value-of select="concat(@uid ,'@crediteasetest.cn')" /></xsl:when><xsl:otherwise><xsl:value-of select="@email" /></xsl:otherwise></xsl:choose></xsl:attribute></param>
                    <param name="account" value="" />                    
                    <param name="open" value="是" />
                    <param name="mobile"><xsl:attribute name="value"></xsl:attribute></param>
                    <param name="department1"><xsl:attribute name="value"><xsl:value-of select="$department1" /></xsl:attribute></param>
                </user>                            
                </xsl:for-each>                
                </xsl:for-each>
           </users>
        </enterprise>
    </xsl:template>
</xsl:stylesheet>