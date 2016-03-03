$(function () {
	$('#myform').validate({     	
		rules : {
						username : {
							required : true,
							minlength : 6,
							remote: {
										url: '/person/checkname',     
										type: "post",                   
										data: {                  
											username: function() {
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
						pass : {
							required : true,
							minlength : 6,
							
						},
                                                notpass : {
                                                         required : true,
                                                                                         minlength : 6,
                                                        equalTo : '#inputPassword', 
                                                },
                                                 truename: {
                                                         required : true,
                                                },                                               
						phone : {
							required : true,
							minlength : 11,
							number:true  ,
						}, 						
						
                  },
                     
		messages : {
			username : {
				required : '帐号不得为空！',
				minlength : jQuery.format('帐号不得小于{0}位！'),
				remote : '帐号已经存在！',
			},
			pass : {
				required : '密码不得为空！',
				minlength : jQuery.format('密码不得小于{0}位！'),
				//remote : '帐号或密码不正确！',
			},  
			notpass : {
				required : '密码不得为空！',
				minlength : jQuery.format('密码不得小于{0}位！'),
				 equalTo:"两次输入的密码不一致！"
			}, 
			truename : {
				required : '姓名不得为空！',
				
			},                        
			phone : {
				required : '手机号不得为空！',
				minlength : jQuery.format('帐号不得小于{0}位！'),

			},	
												           
	    }	
		
	});
});


























