if(typeof(JsInterface) != "undefined"){
	qguid = JsInterface.getGuid();  
	// JsInterface.setGuid(guid);
	JsInterface.setPageUrl(url);        
	JsInterface.setPageTitle(title);        
	JsInterface.setPageDesc(desc);        
	JsInterface.setPageLogo(img);
	JsInterface.getVersion();
	JsInterface.OpenBrowserWithUrl(apkurl);
}
var spread_url = $('#spread').attr('src');
var poster_url = $('#poster').attr('src');
alert(spread_url)
$('#spread').attr('src', spread_url+qguid);
$('#poster').attr('src', poster_url+qguid);
var spread_url = $('#spread').attr('src');
var poster_url = $('#poster').attr('src');
alert(spread_url)