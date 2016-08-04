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