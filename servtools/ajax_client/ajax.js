
function Ajax(){
	var HttpRequest=false;
	var Url=null;
	var ContentType="text";
	this.init=function ()//创建XMLHttpRequest的功能函数
	{
		if (window.ActiveXObject && !window.XMLHttpRequest)
 		{
			window.XMLHttpRequest = function()
	 		{
				var msxmls = ['Msxml2.XMLHTTP.5.0','Msxml2.XMLHTTP.4.0','Msxml2.XMLHTTP.3.0','Msxml2.XMLHTTP','Microsoft.XMLHTTP'];
				for (var i = 0; i < msxmls.length; i++)
		 		{
					try {
							return new ActiveXObject(msxmls[i]);
						}
					catch (e){}
				}
				return null;
			};
		}
		HttpRequest = new XMLHttpRequest();
		if(!HttpRequest)
			{
				return false;
			}
		return HttpRequest;
	}
	this.getType=function (type)//得到请求的类型
	{
		type=type.toUpperCase();
		if(type!="HEAD" && type!="POST" && type!="GET") type="HEAD";
		return type;	
	}
	this.getContentType=function (type)//要得到内容的类型XML/TEXT
	{
		type=type.toLowerCase();
		if("xml"==type)
		{
			ContentType="xml";
			return "text/xml";
		}
		else
		{
			ContentType="text";
		}
		if("text"==type) return "text/plain";
		if("app"==type) return "application/x-www-form-urlencoded";
		return "text/plain";
	}
	this.getInfo=function (url,type,content,send,id,unready)//主要的函数得到内容
	{
		HttpRequest=this.init();
		send=send.replace(/(^\s*)|(\s*$)/g,"");
		type=this.getType(type);
		if(type=='GET')
		{
			 if(url.indexOf("?")>0)
			 {
			 	if(send.substring(0,1)=='&')url=url+send;
			 	else url=url+"&"+send;
			 }
			 else url=url+"?"+send;
		}
		HttpRequest.open(type,url,true);
		HttpRequest.onreadystatechange=function ()//得到更新内容
		{
			
			if(HttpRequest.readyState==4)
			{
				if(HttpRequest.status==200)
				{
					if(!id) return;
					if("HEAD"==type)
					{
						if(id instanceof Function)
						{
							id(HttpRequest.getAllResponseHeaders());
						}
						else
						{
							if(document.getElementById(id))
							{
								document.getElementById(id).innerHTML=HttpRequest.getAllResponseHeaders();
							}
						}
					}
					else
					{
						if("text"==ContentType)
						{
							if(id instanceof Function)
							{
								id(HttpRequest.responseText.replace(/(^\s*)|(\s*$)|(　*)/g , ""));
							}
							else
							{
								if(document.getElementById(id))
								{
									document.getElementById(id).innerHTML="";
									document.getElementById(id).appendChild(changeHTML(HttpRequest.responseText.replace(/(^\s*)|(\s*$)|(　*)/g , "")));
								}
							}
						}
						else
						{
							if(id instanceof Function)
							{
								id(HttpRequest.responseXML);
							}
							else
							{
								if(document.getElementById(id))
								{
									document.getElementById(id).innerHTML=HttpRequest.responseXML;
								}
							}
						}
					}
				}
				else if(HttpRequest.status==404)
				{
					alert("请求的URL地址不存在！");
				}
				else if(HttpRequest.status==403)
				{
					alert("请求的URL地址禁止访问！");
				}
				else if(HttpRequest.status==401)
				{
					alert("请求的URL地址未经受权！");
				}
			}else{
					if(unready instanceof Function)
					{
						unready();
					}
			}
		}
		HttpRequest.setRequestHeader("cache-control","no-cache"); 
		if(this.getType(type)=="POST")
		{
			send=encodeURI(send);
			content="app";
		}
		else
		{
			send=null;
		}
		HttpRequest.setRequestHeader("Content-Type",this.getContentType(content)+";encoding=utf-8");
		HttpRequest.send(send);
	};
}
function changeHTML(html)
{
	var div=document.createElement("div");
	div.innerHTML=html;
	return div;
}