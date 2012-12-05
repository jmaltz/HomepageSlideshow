jQuery(document).ready(function(){
	function isEmpty(form, name)
	{
		return (form.find('input[name="+name+"]').val() === "");
	}

	$("#datepicker").datepicker({
		showOtherMonths: true,
		selectOtherMonths: true,
		autoSize: false,
		dateFormat: "yy-mm-dd"
	});

	$("#timepicker").timePicker({
		show24Hours: false,
		step: 15
	});

	$(".form-horizontal").submit(function(event){
		$('.alert-error').remove();
		var form = $(this);
		$(".alert-error").remove();
		var found_error = false;
		if(isEmpty(form, "title")){
			$("#image_title_controls").append(
					'<div class="span4 alert alert-error">Error, please include an image title</div>'
					);
			found_error = true;
		}

		if(isEmpty(form, "image_alt_text")){
			$("#image_alt_text_controls").append(
					'<div class="span4 alert alert-error">Error, please include image alt text</div>'
					);
			found_error = true;
		}

		if(isEmpty(form, "expiration_date")){
			$("#expiration_date_controls").append(
					'<div class="span4 alert alert-error">Error, please include an expiration date</div>'
					);
			found_error = true;
		}

		if(isEmpty(form, "expiration_time")){
			$("#expiration_time_controls").append(
					'<div class="span4 alert alert-error">Error, please include an expiration time</div>'
					);
			found_error = true;
		}
		if(found_error === true)
		{
			event.preventDefault();
		}
	});
});

