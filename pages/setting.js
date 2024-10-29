function g_aths_submit()
{
	if(document.g_aths_form.g_aths_text.value=="")
	{
		alert(g_aths_adminscripts.g_aths_text);
		document.g_aths_form.g_aths_text.focus();
		return false;
	}
	else if(document.g_aths_form.g_aths_status.value=="")
	{
		alert(g_aths_adminscripts.g_aths_status);
		document.g_aths_form.g_aths_status.focus();
		return false;
	}
	else if(document.g_aths_form.g_aths_order.value=="")
	{
		alert(g_aths_adminscripts.g_aths_order);
		document.g_aths_form.g_aths_order.focus();
		return false;
	}
	else if(isNaN(document.g_aths_form.g_aths_order.value))
	{
		alert(g_aths_adminscripts.g_aths_order);
		document.g_aths_form.g_aths_order.focus();
		return false;
	}
	_g_escapeVal(document.g_aths_form.g_aths_text,'<br>');
}

function g_aths_delete(id)
{
	if(confirm(g_aths_adminscripts.g_aths_delete))
	{
		document.frm_g_aths_display.action="options-general.php?page=announcement-ticker-highlighter-scroller&ac=del&did="+id;
		document.frm_g_aths_display.submit();
	}
}	

function g_aths_redirect()
{
	window.location = "options-general.php?page=announcement-ticker-highlighter-scroller";
}

function g_aths_help()
{
	window.open("http://www.gopiplus.com/work/2010/07/18/announcement-ticker-highlighter-scroller/");
}

function _g_escapeVal(textarea,replaceWith)
{
	textarea.value = escape(textarea.value) //encode textarea strings carriage returns
	for(i=0; i<textarea.value.length; i++)
	{
		//loop through string, replacing carriage return encoding with HTML break tag
		if(textarea.value.indexOf("%0D%0A") > -1)
		{
			//Windows encodes returns as \r\n hex
			textarea.value=textarea.value.replace("%0D%0A",replaceWith)
		}
		else if(textarea.value.indexOf("%0A") > -1)
		{
			//Unix encodes returns as \n hex
			textarea.value=textarea.value.replace("%0A",replaceWith)
		}
		else if(textarea.value.indexOf("%0D") > -1)
		{
			//Macintosh encodes returns as \r hex
			textarea.value=textarea.value.replace("%0D",replaceWith)
		}
	}
	textarea.value=unescape(textarea.value) //unescape all other encoded characters
}