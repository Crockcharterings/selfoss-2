public void setGuid(String guid){
	dp.setGuid(guid);
}
public String getGuid(){
	return dp.getGuid();
}
public void setPageUrl(String url){
	dp.setUrl(url);
}
public void setPageTitle(String title){
	dp.setTitle(title);
}
public void setPageDesc(String desc){
	dp.setDesc(desc);
}
public void setPageLogo(String img){
	Bitmap bmp = getBitmapFromUrl(img);
	dp.setImg(bmp);
}
if(typeof(JsInterface) != "undefined"){
	void guid = JsInterface.getGuid();
	JsInterface.setGuid(guid);
	JsInterface.setGuid(url);
	JsInterface.setGuid(title);
	JsInterface.setGuid(desc);
	JsInterface.setGuid(img);
}