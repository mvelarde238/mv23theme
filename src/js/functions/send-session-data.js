function send_session_data(data){
    clearTimeout(send_session_data_timeout);
    send_session_data_timeout = setTimeout(function () {
    	data.action = 'save_session_data';
        $.ajax({
			type: 'POST',
			dataType : "json",
			url: MV23_GLOBALS.ajaxUrl,
			data : data,
			beforeSend: function(){},
			success: function(response){}
		});
    }, 1000);
}