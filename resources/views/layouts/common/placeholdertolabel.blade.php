<style>                                    
	input:focus + label,
	input:not(:placeholder-shown) + label {
	top: -15px;
	left: 5px;
	/* font-size: 12px; */
	/* color: #007bff; */
	opacity:1;
	padding:0!important;
	font-weight:bold!important;
	background:white;
	}

	label.floatinglabel {
		position: absolute;
		/* top: 12px; */
		/* left: 8px; */
		/* font-size: 16px; */
		/* font-size:inherit; */
		transition: all 0.3s ease;
		pointer-events: none;
		opacity:0.3;
		z-index: 3;
		font-weight:0!important;
	}

</style>
<script>
    $(document).ready(function() {
        $(document).find('.placeholdertolabel').each(function(){
            const title = $(this).attr('placeholder');
            $(this).attr('placeholder',"");
            const padding = $(this).css('padding');
            const label = $('<label class="floatinglabel" style="font-weight:normal;padding:'+padding+'">'+title+'</label>');
            label.insertAfter($(this));
        });
            
        $(this).focus(function() {
        
            $(this).next('label').addClass('focused');
        });
        $(this).on('blur', function() {
            if ($(this).val() === '') {
                $(this).next('label').remove();
            }
        });
        if ($('.placeholdertolabel').val() !== '') {
            $('.placeholdertolabel').next('label').addClass('focused');
        }
        // When the input gains focus, move the label
        $('.placeholdertolabel').on('focus', function() {
            $(this).next('label').addClass('focused');
        });

        // When the input loses focus, but if there's content, keep the label in place
        $('.shifttolabel').on('blur', function() {
            if ($(this).val() === '') {
            $(this).next('label').removeClass('focused');
            }
        });

        // Trigger focus/blur effect based on whether the input has value
        if ($('.shifttolabel').val() !== '') {
            $('.shifttolabel').next('label').addClass('focused');
        }
    });
</script>