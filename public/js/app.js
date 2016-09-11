title = $('title').text();
desc = "快来看我分享给你的网站:" + title;
url = window.location.href;
img = "http://iwebo.portal.net.cn/tongji/icon_small.png";
function setCookie(c_name,value,expiredays)
{
  var exdate=new Date()
  // exdate.setDate(exdate.getDate()+expiredays)
  exdate.setHours(23);
  exdate.setMinutes(59);
  exdate.setSeconds(59);
  document.cookie=c_name+ "=" +escape(value)+
  ((expiredays==null) ? "" : ";path=/;expires="+exdate.toGMTString())
}
function getCookie(c_name)
{
  if (document.cookie.length>0)
    {
    c_start=document.cookie.indexOf(c_name + "=")
    if (c_start!=-1)
      { 
      c_start=c_start + c_name.length+1 
      c_end=document.cookie.indexOf(";",c_start)
      if (c_end==-1) c_end=document.cookie.length
      return unescape(document.cookie.substring(c_start,c_end))
      } 
    }
  return ""
}
if (getCookie('html')) {
} else {
    a = window.location.href.split('?')[1]
    $.get('/htmlid/'+htmlid+'/'+a)
    // setCookie('html'+$('#htmlid').attr('value'), 'ko', 1);
    setCookie('html', 'ko', 1);
}

