/*
*Version: 1.2.1;
*Author: Behaart;
*/
(function($){
	$.fn.ajaxForms=function(o){
		/*---------------------*/
		
		$("#searchform>.submit, #headerSearchForm>.submit").live("click", searchForm);
		$("input:[type='text'], textarea").live("focus", formInputFocus);
		$("input:[type='text'], textarea").live("blur", formInputBlur);
		
		/*----------Focus----------*/		
		function formInputFocus(){
			var item=$(this);
			item.removeClass("errorInput");	
			if(item.data("val")==undefined){
				item.data("val", item.val())
			}
			if(item.val()==item.data("val")){
				item.val("");
			}
		}
		/*----------Blur----------*/
		function formInputBlur(){
			var item=$(this);
			if(item.val()==""){
				item.val(item.data("val"));	
			}
		}
		/*----------Search Form----------*/
		function searchForm(){
			var searchForm = $(this).parent();
			if($(".searching", searchForm).val().length!=0){
				window.location.hash ="#!/?s="+$(".searching", searchForm).val();
			}else{
				$(".searching", searchForm).addClass("errorInput")	
			}
			return false;
		}
		/*--------------------*/
		
		
		$("#commentform>p>#submit").live("click", function(){
			addCommentPost($(this).parent().parent(), {
					author:$(this).parent().parent().find("input:[id='author']").val(),
					email:$(this).parent().parent().find("input:[id='email']").val(),
					url:$(this).parent().parent().find("input:[id='url']").val(),
					comment:$(this).parent().parent().find("textarea:[id='comment']").val(),
					comment_post_ID:$(this).parent().parent().find("input:[id='comment_post_ID']").val(),
					comment_parent:$(this).parent().parent().find("input:[id='comment_parent']").val()				
				}
			)
		 	return false;
		});
		$(".wpcf7 input:[type='submit']").live("click", function(){
				sendMail($(this).parent().parent().serialize(), $(this).parent().parent());
		 		return false;
	 		}
		)
		$(".wpcf7 input:[type='reset']").live("click", function(){
			$(this).parent().parent().find("msg").html("");
		})

		
		function addCommentPost(buttonClick, dataForm){	
			var contactForm = buttonClick,
				dataPost = dataForm;
				
			contactForm.find("#msg").html("Processing...");
			$.ajax({
				url:contactForm.attr("action"),
				type: "POST",
				data:dataPost,
		        success: function(data){
	    			window.location.hash = window.location.hash+"/#?addComent/#?"+parseInt(Math.random()*10);
				},
				error:function(data){
					contactForm.append("<div id='hiddenDiv' style='display:none'>"+data.responseText.substring(data.responseText.indexOf("<p>"), data.responseText.indexOf("</p>"))+"</div>");
					contactForm.find("#msg").html($("#hiddenDiv").find("p").text());
					$("#hiddenDiv").remove();
				}
			})
		}
		function sendMail(dataForm, contactForm){
			var contactFormMail = contactForm,
				dataPost = dataForm;
				
			contactFormMail.find("#msg").html("Processing...");
			$.ajax({
				url:contactFormMail.attr("action"),
				type: "POST",
				data:dataPost,
				async:true,
				dataType:"json",
				error:function(data){
					contactFormMail.find("#msg").html("error");	
				},
				complete:function(data){
					contactFormMail.append("<div id='hiddenDiv' style='display:none'>"+data.responseText+"</div>");
					contactFormMail.find("#msg").html($("#hiddenDiv").find(".wpcf7-response-output").text());
					$("#hiddenDiv").remove();
				}
			})
		}
	}
})(jQuery)