$(function () {
	$('#formToggleLine').validate({     	
		rules : {
						title : {
							required : true,
							remote: {
										url: '/admin/news/checktitle',     
										type: "post",                   
										data: {                  
											title: function() {
												return $("#title").val();
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
						type : {
							required : true,
							
						},                                                                 
						author : {
							required : true,
						}, 						
						
                  },
                     
		messages : {
			title : {
				required : '文章标题不得为空！',
				remote : '帐号已经存在！',
			},
			type: {
				required : '类型不得为空！',

			},  
			author : {
				required : '作者不得为空！',

			}, 
											           
	    }	
		
	});
});


























