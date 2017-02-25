$(".alert-message" ).click(function() {	
	$(this).parent().hide();
});


$("#formAddSupport").ajaxForm({
	beforeSubmit: showReportsRequestFilter, 
    success: showReportsResponseFilter
});

function showReportsRequestFilter(formData, jqForm, options) {
	var success=true;
	$(formData).each(function() {
		if(this.name=="support"){
			if(this.value=="" || this.value==null){
				success=false;
			}
		}
	 });
	 
	if(!success){
		$("#messageText").empty();
		$("#messageText").append("Debe cargar un archivo");
		$("#alertMessage" ).slideDown("slow");
	}else{
		$("#sendSupport").attr("disabled","disabled");
		$("#miniLoader").css('display', 'block');
	}
	
    return success;
}

function showReportsResponseFilter(responseText, statusText, xhr, $form) {
	$("#miniLoader").css('display', 'none');
	$("#support").val('');
	$("#sendSupport").removeAttr("disabled");
	if(responseText!=-1 && $.isNumeric(responseText)){
		$("#supportsSection").empty();
		$("#supportsSection").html($("#miniLoader").clone().removeAttr("id style"));
		
		var params = {
		 	"request-id" : responseText
	    };
	    
	    $.ajax({
	        data:params,
	        url:'/web/concesionario/getSupports',
	        type:  'post',
	        success:  function (response) {
	        	$("#supportsSection").empty();
	        	$("#supportsSection").html(response);
	        	
	        	$("#messageText").empty();
				$("#messageText").append("Se cargó el archivo");
				$("#alertMessage" ).slideDown("slow");
				
				$(".deleteSupport" ).click(function() {	
					var supportId= $(this).attr('rel');
					var params = {
					 	"supportId" : supportId
				    };
				    
				    $.ajax({
				        data:params,
				        url:'/web/concesionario/deleteSupport',
				        type:  'post',
				        success:  function (response) {
							if(response==1){
								$("#support-"+supportId).remove();
								$("#messageText").empty();
								$("#messageText").append("Se ha eliminado la factura o soporte");
							}else{
								$("#messageText").text("Ha ocurrido un error.");
							}
							
							$("#alertMessage" ).slideDown("slow");
				        }
					});
				});
	        }
		});

	}else{
		$("#messageText").text("Ha ocurrido un error");
		$("#alertMessage" ).slideDown("slow");
	}

}

$(".deleteSupport" ).click(function() {	
	$("#miniLoader").css('display', 'block');
	var supportId= $(this).attr('rel');
	var params = {
	 	"supportId" : supportId
    };
    
    $.ajax({
        data:params,
        url:'/web/concesionario/deleteSupport',
        type:  'post',
        success:  function (response) {
			if(response==1){
				$("#support-"+supportId).remove();
				$("#messageText").empty();
				$("#messageText").append("Se ha eliminado la factura o soporte");
			}else{
				$("#messageText").text("Ha ocurrido un error.");
			}
			$("#miniLoader").css('display', 'none');
			$("#alertMessage" ).slideDown("slow");
        }
	});
});