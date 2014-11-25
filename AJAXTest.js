$('#btn1').click(function(){
	$.ajax({

		type: "POST",
		url: "AJAXTest.php",
		data: {'button': '1',
				'input': $('#input1').val()
				},
		success: function(msg){
			$('#DataBindField').html("Data returned:" + msg);
		}
	})
})

$('#btn2').click(function(){
	$.ajax({

		type: "POST",
		url: "AJAXTest.php",
		data: 'button=2',
		success: function(msg){
			$('#DataBindField').html("Data returned:" + msg);
		}
	})
})