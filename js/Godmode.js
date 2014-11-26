// I'M SO SORRY PLEASE DON'T LOOK AT THIS FILE IT'S DISGUSTINGLY BAD AND INEFFICIENT
// I SWEAR I'D REFACTOR IF I HAD TIME! PROMISE!

$('#btnItem_select').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: {'id': 'item_select'
				},
		success: function(msg){
			var tableHeading = "<table class=\"table table-striped\">" + 
								"<thead>" +
								"<tr>" +
								"<th>UPC</th>" + 
								"<th>title</th>" + 
								"<th>item_type</th>" + 
								"<th>category</th>" + 
								"<th>company</th>" + 
								"<th>item_year</th>" + 
								"<th>price</th>" + 
								"<th>stock</th>" +
								"</tr>"+ 
								"</thead>" + 
								"<tbody>";
                $('#modal-body').html(tableHeading + msg + "</tbody></table>");
                $('#myModalLabel').html("Item Table: View");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

$('#btnItem_insert').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: { 'id': 'item_insert',
		   		'new_item_upc': $('#new_item_upc').val(),
		   		'new_item_title': $('#new_item_title').val(),
				'new_item_type': $('#new_item_type').val(),
				'new_item_category': $('#new_item_category').val(),
				'new_item_company': $('#new_item_company').val(),
				'new_item_year': $('#new_item_year').val(),
				'new_item_price': $('#new_item_price').val(),
				'new_item_stock': $('#new_item_stock').val()
				},
		success: function(msg){
                $('#modal-body').html(msg);
                $('#myModalLabel').html("Item Table: Insert");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

$('#btnItem_delete').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: { 'id': 'item_delete',
		   		'del_item_upc': $('#del_item_upc').val(),
				},
		success: function(msg){
                $('#modal-body').html(msg);
                $('#myModalLabel').html("Item Table: Delete");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

$('#btnLeadSinger_select').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: {'id': 'leadSinger_select'
				},
		success: function(msg){
			var tableHeading = "<table class=\"table table-striped\">" + 
								"<thead>" +
								"<tr>" +
								"<th>UPC</th>" + 
								"<th>Name</th>" + 
								"</tr>"+ 
								"</thead>" + 
								"<tbody>";
                $('#modal-body').html(tableHeading + msg + "</tbody></table>");
                $('#myModalLabel').html("LeadSinger Table: View");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

$('#btnLeadSinger_insert').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: { 'id': 'leadSinger_insert',
		   		'new_leadSinger_upc': $('#new_leadSinger_upc').val(),
		   		'new_leadSinger_name': $('#new_leadSinger_name').val()
				},
		success: function(msg){
                $('#modal-body').html(msg);
                $('#myModalLabel').html("LeadSinger Table: Insert");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

$('#btnLeadSinger_delete').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: { 'id': 'leadSinger_delete',
		   		'del_leadSinger_upc': $('#del_leadSinger_upc').val(),
		   		'del_leadSinger_name': $('#del_leadSinger_name').val(),
				},
		success: function(msg){
                $('#modal-body').html(msg);
                $('#myModalLabel').html("LeadSinger Table: Delete");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

$('#btnHasSong_select').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: {'id': 'hasSong_select'
				},
		success: function(msg){
			var tableHeading = "<table class=\"table table-striped\">" + 
								"<thead>" +
								"<tr>" +
								"<th>UPC</th>" + 
								"<th>Title</th>" +
								"</tr>"+ 
								"</thead>" + 
								"<tbody>";
                $('#modal-body').html(tableHeading + msg + "</tbody></table>");
                $('#myModalLabel').html("HasSong Table: View");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

$('#btnHasSong_insert').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: { 'id': 'hasSong_insert',
		   		'new_hasSong_upc': $('#new_hasSong_upc').val(),
		   		'new_hasSong_title': $('#new_hasSong_title').val(),
				},
		success: function(msg){
                $('#modal-body').html(msg);
                $('#myModalLabel').html("HasSong Table: Insert");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

$('#btnHasSong_delete').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: { 'id': 'hasSong_delete',
		   		'del_hasSong_upc': $('#del_hasSong_upc').val(),
		   		'del_hasSong_title': $('#del_hasSong_title').val(),
				},
		success: function(msg){
                $('#modal-body').html(msg);
                $('#myModalLabel').html("HasSong Table: Delete");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

$('#btnOrder_select').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: {'id': 'order_select'
				},
		success: function(msg){
			var tableHeading = "<table class=\"table table-striped\">" + 
								"<thead>" +
								"<tr>" +
								"<th>Receipt ID</th>" + 
								"<th>Order Date</th>" + 
								"<th>CID</th>" + 
								"<th>Card Number</th>" + 
								"<th>Expiry Date</th>" + 
								"<th>Expected Date</th>" + 
								"<th>Delivered Date</th>" + 
								"</tr>"+ 
								"</thead>" + 
								"<tbody>";
                $('#modal-body').html(tableHeading + msg + "</tbody></table>");
                $('#myModalLabel').html("Order Table: View");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

$('#btnOrder_insert').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: { 'id': 'order_insert',
				'new_order_receiptId': $('#new_order_receiptId').val(),
				'new_order_orderDate': $('#new_order_orderDate').val(),
				'new_order_cid': $('#new_order_cid').val(),
				'new_order_cardNumber': $('#new_order_cardNumber').val(),
				'new_order_expiryDate': $('#new_order_expiryDate').val(),
				'new_order_expectedDate': $('#new_order_expectedDate').val(),
				'new_order_deliveredDate': $('#new_order_deliveredDate').val()
								},
		success: function(msg){
                $('#modal-body').html(msg);
                $('#myModalLabel').html("Order Table: Insert");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

$('#btnOrder_delete').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: { 'id': 'order_delete',
		   		'del_order_receiptId': $('#del_order_receiptId').val(),
				},
		success: function(msg){
                $('#modal-body').html(msg);
                $('#myModalLabel').html("Order Table: Delete");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

$('#btnPurchaseItem_select').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: {'id': 'purchaseItem_select'
				},
		success: function(msg){
			var tableHeading = "<table class=\"table table-striped\">" + 
								"<thead>" +
								"<tr>" +
								"<th>Receipt ID</th>" + 
								"<th>UPC</th>" + 
								"<th>Quantity</th>" + 
								"</tr>"+ 
								"</thead>" + 
								"<tbody>";
                $('#modal-body').html(tableHeading + msg + "</tbody></table>");
                $('#myModalLabel').html("PurchaseItem Table: View");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

$('#btnPurchaseItem_insert').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: { 'id': 'purchaseItem_insert',
				'new_purchaseItem_receiptId': $('#new_purchaseItem_receiptId').val(),
				'new_purchaseItem_upc': $('#new_purchaseItem_upc').val(),
				'new_purchaseItem_quantity': $('#new_purchaseItem_quantity').val()
				},
		success: function(msg){
                $('#modal-body').html(msg);
                $('#myModalLabel').html("PurchaseItem Table: Insert");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

$('#btnPurchaseItem_delete').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: { 'id': 'purchaseItem_delete',
		   		'del_purchaseItem_receiptId': $('#del_purchaseItem_receiptId').val(),
		   		'del_purchaseItem_upc': $('#del_purchaseItem_upc').val(),
				},
		success: function(msg){
                $('#modal-body').html(msg);
                $('#myModalLabel').html("PurchaseItem Table: Delete");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

$('#btnCustomer_select').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: {'id': 'customer_select'
				},
		success: function(msg){
			var tableHeading = "<table class=\"table table-striped\">" + 
								"<thead>" +
								"<tr>" +
								"<th>CID</th>" + 
								"<th>Password</th>" + 
								"<th>Name</th>" + 
								"<th>Address</th>" + 
								"<th>Phone</th>" + 
								"</tr>"+ 
								"</thead>" + 
								"<tbody>";
                $('#modal-body').html(tableHeading + msg + "</tbody></table>");
                $('#myModalLabel').html("Customer Table: View");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

$('#btnCustomer_insert').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: { 'id': 'customer_insert',
				'new_customer_cid': $('#new_customer_cid').val(),
				'new_customer_password': $('#new_customer_password').val(),
				'new_customer_name': $('#new_customer_name').val(),
				'new_customer_address': $('#new_customer_address').val(),
				'new_customer_phone': $('#new_customer_phone').val()
				},
		success: function(msg){
                $('#modal-body').html(msg);
                $('#myModalLabel').html("Customer Table: Insert");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

$('#btnCustomer_delete').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: { 'id': 'customer_delete',
		   		'del_customer_cid': $('#del_customer_cid').val()
				},
		success: function(msg){
                $('#modal-body').html(msg);
                $('#myModalLabel').html("Customer Table: Delete");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

$('#btnReturn_select').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: {'id': 'return_select'
				},
		success: function(msg){
			var tableHeading = "<table class=\"table table-striped\">" + 
								"<thead>" +
								"<tr>" +
								"<th>RetID</th>" + 
								"<th>Return Date</th>" + 
								"<th>Receipt ID</th>" + 
								"</tr>"+ 
								"</thead>" + 
								"<tbody>";
                $('#modal-body').html(tableHeading + msg + "</tbody></table>");
                $('#myModalLabel').html("I_Return Table: View");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

$('#btnReturn_insert').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: { 'id': 'return_insert',
				'new_return_retid': $('#new_return_retid').val(),
				'new_return_returnDate': $('#new_return_returnDate').val(),
				'new_return_receiptId': $('#new_return_receiptId').val()
				},
		success: function(msg){
                $('#modal-body').html(msg);
                $('#myModalLabel').html("Return Table: Insert");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

$('#btnReturn_delete').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: { 'id': 'return_delete',
		   		'del_return_retid': $('#del_return_retid').val()
				},
		success: function(msg){
                $('#modal-body').html(msg);
                $('#myModalLabel').html("Return Table: Delete");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

$('#btnReturnItem_select').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: {'id': 'returnItem_select'
				},
		success: function(msg){
			var tableHeading = "<table class=\"table table-striped\">" + 
								"<thead>" +
								"<tr>" +
								"<th>RetID</th>" + 
								"<th>UPC</th>" + 
								"<th>Quantity</th>" + 
								"</tr>"+ 
								"</thead>" + 
								"<tbody>";
                $('#modal-body').html(tableHeading + msg + "</tbody></table>");
                $('#myModalLabel').html("ReturnItem Table: View");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

$('#btnReturnItem_insert').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: { 'id': 'returnItem_insert',
				'new_returnItem_retid': $('#new_returnItem_retid').val(),
				'new_returnItem_upc': $('#new_returnItem_upc').val(),
				'new_returnItem_quantity': $('#new_returnItem_quantity').val()
				},
		success: function(msg){
                $('#modal-body').html(msg);
                $('#myModalLabel').html("ReturnItem Table: Insert");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

$('#btnReturnItem_delete').click(function(){
	$(this).prop('disabled', true);
	$.ajax({

		type: "POST",
		url: "Godmode.php",
		data: { 'id': 'returnItem_delete',
		   		'del_returnItem_retid': $('#del_returnItem_retid').val()
				},
		success: function(msg){
                $('#modal-body').html(msg);
                $('#myModalLabel').html("ReturnItem Table: Delete");
                    $('#myModal').modal('show');
                    $(document).on('hidden.bs.modal', myModal, function (event) {
                        $(this).remove();
                    });
		}
	})
	$(this).prop('disabled', false);
})

//I TOLD YOU NOT TO LOOK PLEASE DON'T JUDGE ME I'M SORRY