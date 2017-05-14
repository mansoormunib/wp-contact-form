(function( $ ) {
	'use strict';
	$(document).ready(function(){

		$("form[name='contact_form_block']").validate({
	      // Specify validation rules
	    rules: {
		   	"name-sscf":{
				required: true,
				minlength: 2
			},
		   	 "email-sscf": {
		   	   required: true,
			   email: true
		   	 }
	      },
	     messages: {
		   	 "name-sscf":{
				 required: "Please enter your name",
				 minlength: "Name should be atleast two charater"
			 },
			 "email-sscf":{
				 required:"Please enter your email",
				 email:"Enter valid email"
			 }
	      },
	      // Make sure the form is submitted to the destination
	      submitHandler: function(form, event) {
			  submit_contat_form_ajax();
			  event.preventDefault();
	      }
	  	});


		function submit_contat_form_ajax() {

	        // get the form data
	        // there are many ways to get this data using jQuery (you can use the class or id also)
	        var formData = {
				'action'		:'submit_contact_form',
	            'name-sscf'          : $('input[name=name-sscf]').val(),
	            'email-sscf'         : $('input[name=email-sscf]').val(),
	            'description-sscf'   : $('textarea[name=description-sscf]').val()
	        };

	        // process the form
	        $.ajax({
	            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
	            url         : ajax_object.ajaxurl, // the url where we want to POST
	            data        : formData, // our data object
				cache		: false, // cache true, false
	        })
	            // using the done promise callback
	            .success(function(data) {

	                // log data to the console so we can see
	                //console.log(data);
					var output = JSON.parse(data);
					var msg_class = "error";

					//console.log(output.status);

					if(output.status == "passed"){
						$("form[name='contact_form_block']").hide();
						msg_class = "no-error";
					}
					$('.contact_form_message').html("<h4 class="+ msg_class +">"+ output[0]+ "</h4>");


	            });
			return false;
	    }

	});

})( jQuery );
