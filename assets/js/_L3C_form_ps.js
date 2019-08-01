/** ********************************************** **
	@Author			SangGu Yoon
	@Website		www.layout3c.com
	@Last Update	2019.04.13
*************************************************** **/

// Log IN Processing
function login_proc()
{
	var params = $("#logInForm").serialize();
	$.ajax({
			url:'/login_proc',
			type:'POST',
			dataType: "json",
			data:params,
			success:function(reponse){
				switch (reponse['result'])
				{
					case "fail":
						$('#login_fail').modal('show');
						break;
					case "success":
						document.location.href	= reponse['redirect_url'];
						break;
				}
			},
			error:function(){
				alert("Server Error !!!");
			}
	});
}


// Rehistration Processing
function register_proc()
{
	var params = $("#memberRegisterForm").serialize();
	$.ajax({
			url:'/register_proc',
			type:'POST',
			dataType: "json",
			data:params,
			success:function(reponse){
				switch (reponse['result'])
				{
					case "duplicated_userid":
						$('#duplicated_user').modal('show');
						break;
					case "fail":
						$('#register_fail').modal('show');
						break;
					case "success":
						document.location.href	= "/";
						break;
				}
			},
			error:function(){
				alert("Server Error !!!");
			}
	}); 
}


// Q&A 1:1 문의  Processing
function one2one_proc()
{
	var params = $("#one2oneForm").serialize();
	$.ajax({
			url:'/one2one_proc',
			type:'POST',
			dataType: "json",
			data:params,
			success:function(reponse){
				switch (reponse['result'])
				{
					case "fail":
						alert("Failt to add entry !!!");
						break;
					case "success":
						$('#one2one_result').modal('show');
						document.location.href	= reponse['redirect_url'];
						break;
				}
			},
			error:function(){
				alert("Server Error !!!");
			}
	});
}
