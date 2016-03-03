$(function () {
	$('#formToggleLine').validate({     	
		rules : {
						classname : {
							required : true,
							remote: {
										url: '/admin/class/checkclassname',     
										type: "post",                   
										data: {                  
											title: function() {
												return $("#classname").val();
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
						typeid:{
							required : true,
							},
						water:{
							required : true,
							}										
						
                  },
                     
		messages : {
			classname : {
				required : '物种名称不得为空！',
				remote : '物种已经存在！',
			},
			typeid : {
				required : '物种类型不得为空！',

			},
			water : {
				required : '物种属性不得为空！',

			},
											           
	    }	
		
	});
});


























