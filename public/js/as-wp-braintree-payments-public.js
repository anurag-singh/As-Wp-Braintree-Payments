jQuery(document).ready(function($){
	$("#package-details li button").click(function(){
		var packageId = $(this).attr('id');
		// alert(packageId);

		$.ajax({
			url 		: 	ajax_object.ajax_url
			,type 		: 	'POST'
			,dataType  	:	'json'
			,data 		:	{
				action	: 	'render_package_details'
				,id		: 	packageId
			}
			,beforeSend : 	function()	{
				console.log("sending");
			}
			,success	:	function(response) {
				console.log(response);
				$('#packageDetails').html(response.html);
			}
		});
	});
});
	
