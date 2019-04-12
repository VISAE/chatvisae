/*
 This file is part of Mibew Messenger project.
 http://mibew.org

 Copyright (c) 2005-2013 Mibew Messenger Community
 License: http://mibew.org/license.php
*/
Ajax.PeriodicalUpdater=Class.create();
Class.inherit(Ajax.PeriodicalUpdater,Ajax.Base,{initialize:function(a){this.setOptions(a);this._options.onComplete=this.requestComplete.bind(this);this._options.onException=this.handleException.bind(this);this._options.onTimeout=this.handleTimeout.bind(this);this._options.timeout=5E3;this.frequency=this._options.frequency||2;this.updater={};this.update()},handleException:function(a,b){this._options.handleError&&this._options.handleError("offline, reconnecting");this.stopUpdate();this.timer=setTimeout(this.update.bind(this),
1E3)},handleTimeout:function(a){this._options.handleError&&this._options.handleError("timeout, reconnecting");this.stopUpdate();this.timer=setTimeout(this.update.bind(this),1E3)},stopUpdate:function(){this.updater._options&&(this.updater._options.onComplete=void 0);clearTimeout(this.timer)},update:function(){this._options.updateParams&&(this._options.parameters=this._options.updateParams());this.updater=new Ajax.Request(this._options.url,this._options)},requestComplete:function(a){try{var b=Ajax.getXml(a);
b?(this._options.updateContent||Ajax.emptyFunction)(b):this._options.handleError&&this._options.handleError("reconnecting")}catch(c){}this.timer=setTimeout(this.update.bind(this),1E3*this.frequency)}});
var HtmlGenerationUtils={popupLink:function(a,b,c,d,e,m,l){return'<a href="'+a+'"'+(null!=l?' class="'+l+'"':"")+' target="_blank" title="'+b+'" onclick="this.newWindow = window.open(\''+a+"', '"+c+"', 'toolbar=0,scrollbars=0,location=0,status=1,menubar=0,width="+e+",height="+m+",resizable=1');this.newWindow.focus();this.newWindow.opener=window;return false;\">"+d+"</a>"},generateOneRowTable:function(a){return'<table class="inner"><tr>'+a+"</tr></table>"},viewOpenCell:function(a,b,c,d,e,m,l,p){m=
2;b=b+"?thread="+c;var f="<td>",f=e||d?f+HtmlGenerationUtils.popupLink(p||!d?b:b+"&viewonly=true",localized[e?0:1],"ImCenter"+c,a,640,650,null):f+('<a href="#">'+a+"</a>"),f=f+"</td>";e&&(f=f+'<td class="icon">'+HtmlGenerationUtils.popupLink(b,localized[0],"ImCenter"+c,'<img src="'+mibewRoot+'/images/tbliclspeak.gif" width="15" height="15" border="0" alt="'+localized[0]+'">',640,650,null),f+="</td>",m++);d&&(f+='<td class="icon">',f+=HtmlGenerationUtils.popupLink(b+"&viewonly=true",localized[1],"ImCenter"+
c,'<img src="'+mibewRoot+'/images/tbliclread.gif" width="15" height="15" border="0" alt="'+localized[1]+'">',640,650,null),f+="</td>",m++);""!=l&&(f+='</tr><tr><td class="firstmessage" colspan="'+m+'"><a href="javascript:void(0)" title="'+l+'" onclick="alert(this.title);return false;">',f+=30<l.length?l.substring(0,30)+"...":l,f+="</a></td>");return HtmlGenerationUtils.generateOneRowTable(f)},banCell:function(a,b){return'<td class="icon">'+HtmlGenerationUtils.popupLink(mibewRoot+"/operator/ban.php?"+
(b?"id="+b:"thread="+a),localized[2],"ban"+a,'<img src="'+mibewRoot+'/images/ban.gif" width="15" height="15" border="0" alt="'+localized[2]+'">',720,650,null)+"</td>"}};Ajax.ThreadListUpdater=Class.create();
Class.inherit(Ajax.ThreadListUpdater,Ajax.Base,{initialize:function(a){this.setOptions(a);this._options.updateParams=this.updateParams.bind(this);this._options.handleError=this.handleError.bind(this);this._options.updateContent=this.updateContent.bind(this);this._options.lastrevision=0;this.threadTimers={};this.delta=0;this.t=this._options.table;this.periodicalUpdater=new Ajax.PeriodicalUpdater(this._options)},updateParams:function(){return"since="+this._options.lastrevision+"&status="+this._options.istatus+
(this._options.showonline?"&showonline=1":"")},setStatus:function(a){this._options.status.innerHTML=a},handleError:function(a){this.setStatus(a)},updateThread:function(a){function b(a,b,c,d){if(a=CommonUtils.getCell(c,b,a))a.innerHTML=d}for(var c,d,e,m=!1,l=!1,p=!1,f=null,q=null,g=0;g<a.attributes.length;g++){var n=a.attributes[g];"id"==n.nodeName?c=n.nodeValue:"stateid"==n.nodeName?d=n.nodeValue:"state"==n.nodeName?e=n.nodeValue:"canopen"==n.nodeName?l=!0:"canview"==n.nodeName?m=!0:"canban"==n.nodeName?
p=!0:"ban"==n.nodeName?f=n.nodeValue:"banid"==n.nodeName&&(q=n.nodeValue)}g=CommonUtils.getRow("thr"+c,this.t);if("closed"==d)g&&this.t.deleteRow(g.rowIndex),this.threadTimers[c]=null;else{var n=NodeUtils.getNodeValue(a,"name"),u=NodeUtils.getNodeValue(a,"addr"),s=NodeUtils.getNodeValue(a,"time"),v=NodeUtils.getNodeValue(a,"agent"),t=NodeUtils.getNodeValue(a,"modified"),w=NodeUtils.getNodeValue(a,"message"),r="<td>"+NodeUtils.getNodeValue(a,"useragent")+"</td>";null!=f&&(r="<td>"+NodeUtils.getNodeValue(a,
"reason")+"</td>");p&&(r+=HtmlGenerationUtils.banCell(c,q));r=HtmlGenerationUtils.generateOneRowTable(r);a=CommonUtils.getRow("t"+d,this.t);p=CommonUtils.getRow("t"+d+"end",this.t);null!=g&&(g.rowIndex<=a.rowIndex||g.rowIndex>=p.rowIndex)&&(this.t.deleteRow(g.rowIndex),g=this.threadTimers[c]=null);if(null==g){if(g=this.t.insertRow(a.rowIndex+1),g.className="blocked"==f&&"chat"!=d?"ban":"in"+d,g.id="thr"+c,this.threadTimers[c]=[s,t,d],CommonUtils.insertCell(g,"name","visitor",null,null,HtmlGenerationUtils.viewOpenCell(n,
this._options.agentservl,c,m,l,f,w,"chat"!=d)),CommonUtils.insertCell(g,"contid","visitor","center",null,u),CommonUtils.insertCell(g,"state","visitor","center",null,e),CommonUtils.insertCell(g,"op","visitor","center",null,v),CommonUtils.insertCell(g,"time","visitor","center",null,this.getTimeSince(s)),CommonUtils.insertCell(g,"wait","visitor","center",null,"chat"!=d?this.getTimeSince(t):"-"),CommonUtils.insertCell(g,"etc","visitor","center",null,r),"wait"==d||"prio"==d)return!0}else this.threadTimers[c]=
[s,t,d],g.className="blocked"==f&&"chat"!=d?"ban":"in"+d,b(this.t,g,"name",HtmlGenerationUtils.viewOpenCell(n,this._options.agentservl,c,m,l,f,w,"chat"!=d)),b(this.t,g,"contid",u),b(this.t,g,"state",e),b(this.t,g,"op",v),b(this.t,g,"time",this.getTimeSince(s)),b(this.t,g,"wait","chat"!=d?this.getTimeSince(t):"-"),b(this.t,g,"etc",r);return!1}},updateQueueMessages:function(){function a(a,b){var c=$(b),l=$(b+"end");return null==c||null==l?!1:c.rowIndex+1<l.rowIndex}var b=$("statustd");if(b){var c=a(this.t,
"twait")||a(this.t,"tprio")||a(this.t,"tchat");b.innerHTML=c?"":this._options.noclients;b.height=c?5:30}},getTimeSince:function(a){a=Math.floor(((new Date).getTime()-a-this.delta)/1E3);var b=Math.floor(a/60),c="";a%=60;10>a&&(a="0"+a);60<=b&&(c=Math.floor(b/60),b%=60,10>b&&(b="0"+b),c+=":");return c+b+":"+a},updateTimers:function(){for(var a in this.threadTimers)if(null!=this.threadTimers[a]){var b=this.threadTimers[a],c=CommonUtils.getRow("thr"+a,this.t);if(null!=c){var d=this.getTimeSince(b[0]),
e=CommonUtils.getCell("time",c,this.t);e&&(e.innerHTML=d);b="chat"!=b[2]?this.getTimeSince(b[1]):"-";if(c=CommonUtils.getCell("wait",c,this.t))c.innerHTML=b}}},updateThreads:function(a){var b=!1,c=NodeUtils.getAttrValue(a,"time"),d=NodeUtils.getAttrValue(a,"revision");c&&(this.delta=(new Date).getTime()-c);d&&(this._options.lastrevision=d);for(c=0;c<a.childNodes.length;c++)d=a.childNodes[c],"thread"==d.tagName&&this.updateThread(d)&&(b=!0);this.updateQueueMessages();this.updateTimers();this.setStatus(this._options.istatus?
"Away":"Up to date");b&&(playSound(mibewRoot+"/sounds/new_user.wav"),window.focus(),updaterOptions.showpopup&&alert(localized[5]))},updateOperators:function(a){var b=$("onlineoperators");if(b){for(var c=[],d=0;d<a.childNodes.length;d++){var e=a.childNodes[d];if("operator"==e.tagName){var m=NodeUtils.getAttrValue(e,"name"),e=null!=NodeUtils.getAttrValue(e,"away");c[c.length]='<img src="'+mibewRoot+"/images/op"+(e?"away":"online")+'.gif" width="12" height="12" border="0" alt="'+localized[1]+'"> '+m}}b.innerHTML=
c.join(", ")}},updateContent:function(a){if("update"==a.tagName)for(var b=0;b<a.childNodes.length;b++){var c=a.childNodes[b];"threads"==c.tagName?this.updateThreads(c):"operators"==c.tagName&&this.updateOperators(c)}else"error"==a.tagName?this.setStatus(NodeUtils.getNodeValue(a,"descr")):this.setStatus("reconnecting")}});
function togglemenu(){$("sidebar")&&$("wcontent")&&$("togglemenu")&&("contentnomenu"==$("wcontent").className?($("sidebar").style.display="block",$("wcontent").className="contentinner",$("togglemenu").innerHTML=localized[4]):($("sidebar").style.display="none",$("wcontent").className="contentnomenu",$("togglemenu").innerHTML=localized[3]))}var mibewRoot="";Behaviour.register({"#togglemenu":function(a){a.onclick=function(){togglemenu()}}});
EventHelper.register(window,"onload",function(){mibewRoot=updaterOptions.wroot;new Ajax.ThreadListUpdater({table:$("threadlist"),status:$("connstatus"),istatus:0}.extend(updaterOptions||{}));updaterOptions.havemenu||togglemenu()});
