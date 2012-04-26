$(document).ready(function(){
	$("#cancel").click(function() {
		$(location).attr('href',"/admin/?showAll=1");
	});
	
	$("#titel_ater").click(function() {
		$("#titel").val($("#titel_orig").val());
	});
	$("#bik_ater").click(function() {
		$("#bik").val($("#bik_orig").val());
	});

    $("#spara").click(function() {
        $.post("post.php", $("#postform").serialize());
		history.back();
    });
});