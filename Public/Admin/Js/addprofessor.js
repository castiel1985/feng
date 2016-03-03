$(function () {
	$('#formToggleLine').validate({     	
		rules : {
						username : {
							required : true,
							remote: {
										url: '/admin/user/check',     
										type: "post",                   
										data: {                  
											title: function() {
												return $("#username").val();
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
						job:{
							required : true,
							},
						job_unit:{
							required : true,
							}	,
						skilled:{
							required : true,
							}	,
																
						mail:{
							required : true,
							}	,	
						phone:{
							required : true,
							}	,
						intro:{
							required : true,
							}	,
						truename:{
							required : true,
							}	,
						password:{
							required : true,
							}	,	
                  },
                     
		messages : {
			username : {
				required : '专家帐号不得为空！',
				remote : '专家帐号已经存在！',
			},
			job : {
				required : '专家职位不得为空！',

			},
			job_unit: {
				required : '专家工作单位不得为空！',

			},			
			skilled : {
				required : '专家技能不得为空！',

			},
			mail : {
				required : '邮箱不得为空！',

			},
			phone : {
				required : '电话不得为空！',

			},	
			truename: {
				required : '专家真实姓名不得为空！',

			},		
			intro : {
				required : '专家介绍不得为空！',

			},													           
	    }	
		
	});
});


























