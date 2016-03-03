$(function () {
	$('#formToggleLine').validate({     	
		rules : {
						name : {
							required : true,
							remote: {
										url: '/admin/diseases/check',     
										type: "post",                   
										data: {                  
											title: function() {
												return $("#name").val();
											}
										},
										dataType: "html",
										dataFilter: function(data) {
											if(data==0)
											return true;
											else return false;
										}
									}
						}, 
						type:{
							required : true,
							},
						pid:{
							required : true,
							}										
						
                  },
                     
		messages : {
			name : {
				required : '疾病名称不得为空！',
				remote : '疾病已经存在！',
			},
			type : {
				required : '物种类型不得为空！',

			},
			pid : {
				required : '疾病种类不得为空！',

			},
											           
	    }	
		
	});
});


























