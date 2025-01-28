function openDialog(title,url,id) {
	if(!id) var id = '';
	//
	if( $("#"+ id +"").dialog("isOpen")===true ){
	 $("#"+ id +"").dialogExtend("restore");
	 $("#"+ id +"").dialog("open");
	} else {
		   $('<html>').dialog({
			modal: false,
			resizable: true,
			autoOpen: true,
			position: { my: "center", at: "center", of: window },
			open: function ()
			{
				var idtag = $(this);
				$(this).html('<center><img src="images/loader-bar.gif"></center>');
		$.ajax({
			type: "GET",
			url: url,
			timeout: 8000, // in milliseconds
			success: function(data) {
				$(idtag).html(data);
			},
			error: function(x, t, m) {
				if(t==="timeout") {
					alert("Kan data niet openen.");
				} else {
					alert(t);
				}
			}
		})
			},  
			close: function(event, ui)
			{
				$(this).dialog('destroy').remove()
			},
			height: 500,
			width: 600,
			title: title
		}).dialogExtend({
			"closable" : true,
			"minimizable" : true,
			"collapsable" : true
		  }).attr('id', id).parent();
	};
    }