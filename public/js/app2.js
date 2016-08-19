if(typeof(JsInterface) != "undefined"){
	void guid = JsInterface.getGuid();   
	JsInterface.setGuid(guid);
	JsInterface.setPageUrl(url);        
	JsInterface.setPageTitle(title);        
	JsInterface.setPageDesc(desc);        
	JsInterface.setPageLogo(img);
	var ver = JsInterface.getVersion();
    if (ver > version) {
        JsInterface.OpenBrowserWithUrl(apkurl);
    };
}
url0 = $('iframe').eq(0).attr('src');
url1 = $('iframe').eq(1).attr('src');
$('iframe').eq(0).attr('src', guid+url0)
$('iframe').eq(1).attr('src', guid+url1)