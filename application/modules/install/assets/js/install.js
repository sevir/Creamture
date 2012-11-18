$(function(){
	$("#htaccess_form").submit(function(){
		$.getJSON(INSTALL_PATH+"/saveHtaccess", {"htaccess":$("#htaccess").val()}, function(data){
			$("#ajax-msg").html(
				"<p class='alert alert_"+((data.stat)?"info":"error")+"'>"+data.msg+"</p>"
			);
		});

		return false;
	});

	$("#removeHtaccess").click(function(){
		$.getJSON(INSTALL_PATH+"/removeHtaccess", function(data){
			$("#ajax-msg").html(
				"<p class='alert alert_"+((data.stat)?"info":"error")+"'>"+data.msg+"</p>"
			);
		});

		return false;
	});
});