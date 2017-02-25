$(".datepicker").datepicker({ dateFormat: "dd-mm-yy" });

$( "#filterCity" ).change(function() {
	$("#request").attr("disabled","disabled");
	$("#filterDealership").attr("disabled","disabled");
	
	var city=$(this).val();
	var params = {
	 	"city" : city
    };
	    
    $.ajax({
	    data:params,
	    url:'/web/reports/onChangeCity',
	    type:  'post',
	    success:  function (response) {
	    	
	    	var dealshipers= $.parseJSON(response);
			var currentDealshiper=$('#filterDealership').val();
        	$('#filterDealership').empty();
        	$('#filterDealership').append($("<option>").attr('value',0).text("Seleccione"));

			$(dealshipers).each(function() {
				$('#filterDealership').append($("<option>").attr('value',this.Group.id).text(this.Group.name));
			});
			
			$("#filterDealership").val(currentDealshiper).prop('selected', true);
			$("#request").removeAttr("disabled");
			$("#filterDealership").removeAttr("disabled");
	    }
	});
	
});

$( "#filterDealership" ).change(function() {
	$("#request").attr("disabled","disabled");
	$("#filterCity").attr("disabled","disabled");
	
	var dealshiper=$(this).val();
	var params = {
	 	"dealshiper" : dealshiper
    };
	    
    $.ajax({
	    data:params,
	    url:'/web/reports/onChangeDealshiper',
	    type:  'post',
	    success:  function (response) {
	    	
	    	var cities= $.parseJSON(response);
			var currentCity=$('#filterCity').val();
        	$('#filterCity').empty();
        	$('#filterCity').append($("<option>").attr('value',0).text("Seleccione"));

			$(cities).each(function() {
				$('#filterCity').append($("<option>").attr('value',this.Group.city).text(this.Group.city));
			});
			
			$("#filterCity").val(currentCity).prop('selected', true);
			//$('#filterCity option[value=\''+currentCity+'\']').attr('selected','selected');
			$("#request").removeAttr("disabled");
			$("#filterCity").removeAttr("disabled");
	    }
	});
	
});

$("#formReportsFilter").ajaxForm({
	beforeSubmit: showReportsRequestFilter, 
    success: showReportsResponseFilter
});

//-----------------------------------------------------------------------------------------------------------------
function showReportsRequestFilter(formData, jqForm, options) {
	$("#filteredReports").html($("#loader-container").clone().removeAttr("id style"));
	var success=true;
	var startDate = null;
	var endDate = null;
	var errorMessage="";
	
	$(formData).each(function() {
		if(this.name=="start"){
			if(this.value!=""){
				startDate=this.value;
			}
		}else if(this.name=="end"){
			if(this.value!=""){
				endDate=this.value;
			}
		}
	 });
	 
	 if(startDate!=null && endDate!=null){
	 	startDate=startDate.split("-").reverse().join("-");
	 	endDate=endDate.split("-").reverse().join("-");
	 	 if(new Date(startDate) > new Date(endDate)){
	 	 	errorMessage+="Fecha fin debe ser mayor que fecha inicio ";
			success=false;
	 	 }
	 }
	 
	 if(!success){
	 	alert(errorMessage);
	 	$("#filteredReports").empty();
	 }

    return success;
}

//----------------------------------------------------------------------------------------------------------------- 
function showReportsResponseFilter(responseText, statusText, xhr, $form) {
	$("#filteredReports").html(responseText);
}
