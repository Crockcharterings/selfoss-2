if(typeof(JsInterface) != "undefined"){
	qguid = JsInterface.getGuid(); 
	if(!qguid) {qguid=null}
	// JsInterface.setGuid(guid);
	JsInterface.setPageUrl(url+'?'+qguid+'?');
	// alert(url+'?'+qguid)
	JsInterface.setPageTitle(title);        
	JsInterface.setPageDesc(desc);        
	JsInterface.setPageLogo(img);

	var spread_url = $('#spread').attr('src');
	var poster_url = $('#poster').attr('src');
	$('#spread').attr('src', spread_url+qguid);
	$('#poster').attr('src', poster_url+qguid);
	var spread_url = $('#spread').attr('src');
	var poster_url = $('#poster').attr('src');
} else {
	uguid = window.location.href.split('?')[1];
	uguid = '1470879327';
	var spread_url = $('#spread').attr('src');
	var poster_url = $('#poster').attr('src');
	$('#spread').attr('src', spread_url+uguid);
	$('#poster').attr('src', poster_url+uguid);
	var spread_url = $('#spread').attr('src');
	var poster_url = $('#poster').attr('src');
}
$('#poster').height("100%")