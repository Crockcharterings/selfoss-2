if(typeof(JsInterface) != "undefined"){
	qguid = JsInterface.getGuid(); 
	if(!qguid) {
		qguid=null
		u = window.location.href;
		alert(u);
		u.split('?');
		qguid = u[1];
	}
	// JsInterface.setGuid(guid);
	JsInterface.setPageUrl(url+'?'+qguid);
	alert(url+'?'+qguid)
	JsInterface.setPageTitle(title);        
	JsInterface.setPageDesc(desc);        
	JsInterface.setPageLogo(img);

	var spread_url = $('#spread').attr('src');
	var poster_url = $('#poster').attr('src');
	// alert(spread_url) 获取原始url
	$('#spread').attr('src', spread_url+qguid);
	$('#poster').attr('src', poster_url+qguid);
	var spread_url = $('#spread').attr('src');
	var poster_url = $('#poster').attr('src');
	alert(spread_url) 拼接url上guid参数
}
