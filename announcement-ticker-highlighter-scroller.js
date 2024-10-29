/**
 *     Announcement ticker highlighter scroller
 *     Copyright (C) 2011- 2021 www.gopiplus.com
 *     http://www.gopiplus.com/work/2010/07/18/announcement-ticker-highlighter-scroller/
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


var g_aths_current=0
var g_aths_clipbottom=1

function g_aths_changecontent(){
msgheight=g_aths_clipbottom=crosstick.offsetHeight
crosstick.style.clip="rect("+msgheight+"px auto auto 0px)"
crosstickbg.innerHTML=g_aths_contents[g_aths_current]
crosstick.innerHTML=g_aths_contents[g_aths_current]
g_aths_highlightmsg()
}

function g_aths_highlightmsg(){
//var msgheight=crosstick.offsetHeight
if (g_aths_clipbottom>0){
g_aths_clipbottom-=g_aths_speed
crosstick.style.clip="rect("+g_aths_clipbottom+"px auto auto 0px)"
beginclip=setTimeout("g_aths_highlightmsg()",20)
}
else{
g_aths_clipbottom=msgheight
clearTimeout(beginclip)
if (g_aths_current==g_aths_contents.length-1) g_aths_current=0
else g_aths_current++
setTimeout("g_aths_changecontent()",g_aths_delay)
}
}

function g_aths_startcontent(){
crosstickbg=document.getElementById? document.getElementById("g_aths_bg") : document.all.g_aths_bg
crosstick=document.getElementById? document.getElementById("g_aths_highlighter") : document.all.g_aths_highlighter
crosstickParent=crosstick.parentNode? crosstick.parentNode : crosstick.parentElement
if (parseInt(crosstick.offsetHeight)>0)
crosstickParent.style.height=crosstick.offsetHeight+'px'
else
setTimeout("crosstickParent.style.height=crosstick.offsetHeight+'px'",100) //delay for Mozilla's sake
g_aths_changecontent()
}