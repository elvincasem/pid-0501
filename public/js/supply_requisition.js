$('#addreq').click(function(){
		//clear fields
		//alert("clear");
		//document.getElementById("rdate").value = "";
		document.getElementById("rno").value = "";
		document.getElementById("requester_id").value = ""
		
		
		var lastreq = document.getElementById("lastreq").value;
		var year = new Date().getFullYear();
		var prefix = "RIS";
	
		var splits = lastreq.split("-");
		var lastyear = splits[0].substring(3, 8);
		
		if(lastyear!=year){
			//start to 1
			
			var lastreqno = 1;
		}else{
			var lastreqno = parseInt(splits[1]);
		}
		
		var increment1 = lastreqno+1;
		var strinc = increment1.toString();
		
		if(strinc.length==1){
			var zero ="0000";
		}
		if(strinc.length==2){
			var zero ="000";
		}
		if(strinc.length==3){
			var zero ="00";
		}
		if(strinc.length==4){
			var zero ="0";
		}
		if(strinc.length==5){
			var zero ="0";
		}
		if(strinc.length==6){
			var zero ="";
		}
		
		//if(increment1.length )
		
		
		var displayreqno = prefix+year+"-"+zero+increment1;
	
		document.getElementById("rno").value = displayreqno;
		//console.log(lastyear);

	});
	
function savereqeuisition(){
	
	var rdate = document.getElementById("rdate").value;
	var rno = document.getElementById("rno").value;
	var requester_id = document.getElementById("requester_id").value;
	var purpose = document.getElementById("purpose").value;
	var warehouseid = document.getElementById("warehouseid").value;
	
	$.ajax({
					url: 'requisition/saverequisition',
					type: 'post',
					data: {rdate:rdate,rno:rno,requester_id:requester_id,purpose:purpose,warehouseid:warehouseid},
					success: function(response) {
						var lastid = JSON.parse(response);
						window.location.href = "requisition/details/"+lastid;
					}
					});
					
					
}

function additemreq(){
	
	var itemid = document.getElementById("itemid").value;
	var reqid = document.getElementById("reqid").value;
	var itemqty = document.getElementById("itemqty").value;
	var itemunit = document.getElementById("itemunit").value;
	var requisition_no = document.getElementById("requisition_no").value;
	$.ajax({
					url: '../additemreq',
					type: 'post',
					data: {itemid:itemid,requisition_no:requisition_no,itemqty:itemqty,itemunit:itemunit,reqid:reqid},
					success: function(response) {
						
						$('#general-table').load(document.URL +  ' #general-table');
						$.bootstrapGrowl('<h4><strong>Success!</strong></h4> <p>Item Updated!</p>', {
							type: 'success',
							delay: 3000,
							allow_dismiss: true,
							offset: {from: 'top', amount: 20}
						});
					}
					});
	
}


function deletereqitem(id){
	//alert(id);
	var r = confirm("Are your sure you want to delete this item?");
    if (r == true) {
        //alert ("You pressed OK!");
		var person = prompt("Please enter Administrator Password");
		if (person =='superadmin') {
		$.ajax({
                    url: '../deletereqitem',
                    type: 'post',
                    data: {reqitem: id},
                    success: function(response) {
						console.log(response);
						$('#general-table').load(document.URL +  ' #general-table');
						$.bootstrapGrowl('<h4><strong>Success!</strong></h4> <p>Item Deleted!</p>', {
							type: 'warning',
							delay: 3000,
							allow_dismiss: true,
							offset: {from: 'top', amount: 20}
						});
                    }
                });
		}else{
			alert("Invalid Password");
		}
		
    } if(r == false) {
        //txt = "You pressed Cancel!";
		
    }
	
}

function showitemunit(){
	var itemid = document.getElementById("itemid").value;
	var unitselect = document.getElementById("itemunit");
	//var inventory_qty = document.getElementById("inventory_qty");
	
	for (var i = 0; i <= unitselect.length; i++) { 
		unitselect.remove(unitselect.length-1);
	}
	$.ajax({
		url: '../showitemunit',
		type: 'post',
		data: {itemid:itemid},
		success: function(response) {
			console.log(response);
			var uom = JSON.parse(response);
			//console.log(uom);
			//console.log(uom.unit);
			//document.getElementById("itemunit").innerHTML = uom.unit;
			document.getElementById("inventory_qty").value=uom.inventory_qty;
			
			$("#itemunit").append("<option value='"+uom.unit+"'>"+uom.unit+"</option>");
	}
	});
}

function printreq()
{
	var DocumentContainer = document.getElementById('reqprintbody');
	var WindowObject = window.open("", "PrintWindow",
	"width=750,height=650,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes");
	WindowObject.document.writeln(DocumentContainer.innerHTML);
	WindowObject.document.close();
	setTimeout(function(){
		WindowObject.focus();
		WindowObject.print();
		WindowObject.close();
	},50);
}

function deleterequisition(reqid){
	//alert(id);
	var r = confirm("Are your sure you want to delete this requisition?");
    if (r == true) {
        //alert ("You pressed OK!");
		var person = prompt("Please enter Administrator Password");
		if (person =='superadmin') {
		$.ajax({
                    url: 'requisition/deleterequisition',
                    type: 'post',
                    data: {reqid:reqid},
                    success: function(response) {
						console.log(response);
						location.reload();
                    }
                });
		}else{
			alert("Invalid Password");
		}
		
    } if(r == false) {
        //txt = "You pressed Cancel!";
		
    }
	
}

function updateinventory(reqid){
	
	//var requisition_no = reqno;
	//console.log(requisition_no);
	$.ajax({
		url: '../updateinventory',
		type: 'post',
		data: {reqid:reqid},
		success: function(response) {
			console.log(response);
						$('#general-table').load(document.URL +  ' #general-table');
						$.bootstrapGrowl('<h4><strong>Success!</strong></h4> <p>Item Updated!</p>', {
							type: 'success',
							delay: 3000,
							allow_dismiss: true,
							offset: {from: 'top', amount: 20}
						});
			location.reload();
			
		}
	});
	
	
}

function editreq(){
	
	//$('#requisition_no').prop("disabled", false);    
	$('#rdate').prop("disabled", false);    
	$('#employeeid').prop("disabled", false);    
	$('#status').prop("disabled", false);    
	$('#purpose').prop("disabled", false);    
	//$('#warehouseid').prop("disabled", false);    
}

function updatereqdetails(){
	
	var rdate = document.getElementById("rdate").value;
	var status = document.getElementById("status").value;
	var requester_id = document.getElementById("employeeid").value;
	var purpose = document.getElementById("purpose").value;
	var reqid = document.getElementById("reqid").value;
	var warehouseid = document.getElementById("warehouseid").value;
	
	$.ajax({
					url: '../updatequisitiondetails',
					type: 'post',
					data: {rdate:rdate,status:status,requester_id:requester_id,purpose:purpose,reqid:reqid,warehouseid:warehouseid},
					success: function(response) {
						
						location.reload();
						//var lastid = JSON.parse(response);
						//window.location.href = "requisition/details/"+lastid;
					}
					});
					
					
}


