@php 
    $edit = $edit??false;
@endphp
<script>
    $(document).on('input.art','.tag',function(){
        const index = itemcheck($(this));
    });

    $(document).on('input.art','.remark',function(){
        itemcheck($(this));
    });

    $(document).on('input.art','.piece',function(){
        const index =  itemcheck($(this));
        if($('.piece').val() == ''){
            toastr.error("Enter the Pieces !");
            $('.piece').eq(index).addClass('is-invalid').focus();
            //return false;
        }else{
            $('.piece').eq(index).val($('.piece').eq(index).val().replace(/\D/g, ''));
        }
        calculateitemsum(index);
    });

    $(document).on('input.art','.rate',function(){
        const index =  itemcheck($(this));
        if($('.rate').val() != ''){
            if($('.piece').eq(index).val() == ''){
                toastr.error("Enter the Pieces !");
                $(this).val("");
                $('.piece').eq(index).addClass('is-invalid').focus();
                return false;
            }
        }else{
             toastr.error("Enter the Rate !");
            $('.rate').eq(index).addClass('is-invalid').focus();
            return false;
        }
        calculateitemsum(index);
    });

    $(document).on('input.art','.other',function(){
        const index =  itemcheck($(this));
        
        calculateitemsum(index);
    });

    $(document).on('input.art','.disc',function(){
        const index =  itemcheck($(this));
        calculateitemsum(index);
    });

    $(document).on('change.art','.discunit',function(){
        const index =  itemcheck($(this));
        calculateitemsum(index);
    });

    $(document).on('change.art','.image',function(){
        const index =  itemcheck($(this));
    });

    function itemcheck(element){
        const index = element.closest('tr').index();
        @if(!$edit)
        const item = $(document).find('.item').eq(index)
        const item_name = item.val()??false;
        if(item_name){
            return index;
        }else{
            element.val("");
            toastr.error("Please Select the Item Name First !");
            item.addClass('blank-required').focus();
            return;
        }
        @else 
        return index;
        @endif
    }

    function calculateitemsum(index){
        const piece = $('.piece').eq(index).val()??0;
        const rate = $('.rate').eq(index).val()??0;
        const other = $('.other').eq(index).val()??0;
        let disc = $('.disc').eq(index).val()??false;
        const unit = $('.discunit').eq(index).val()??false;
		if(piece == 0 && rate == 0){
            toastr.error('Piece & Rate required !');
            return false;
        }
        let mid_ttl = +piece * +rate;
        mid_ttl = + mid_ttl + +other;
        if(disc){
            if(unit){
                if(unit=='p'){
                   disc =  +((+mid_ttl * +disc)/100);
                }
            }else{
                toastr.error("Discount Unit  Required !");
                $('.discunit').eq(index).addClass('blank-required');
            }
        }
        mid_ttl -= +disc;
        mid_ttl = mid_ttl.toFixed(2);
        $(".ttl").eq(index).val(mid_ttl??0);
        calculatestocksum();
    }

    /*function calculatestocksum(){
        let count = 0;
        let row_ttl = row_pcs = row_othr = 0;
        $('.piece').each(function(i,v){
            row_pcs+= +($(v).val()??0);
        });
        $('.other').each(function(i,v){
            row_othr+= +($(v).val()??0);
        });
        $('.ttl').each(function(i,v){
            row_ttl+= +($(v).val()??0);
            if($(v).val()!=""){
                count++;
            }
        });
        $("#list_item").val(count+" Item");
        $("#list_piece").val(row_pcs);
        $("#list_other").val(row_othr);
        $("#list_total").val(row_ttl);
    }*/
	
	function calculatestocksum(){
		let count = 0;
        let row_ttl = row_pcs = row_othr = 0;
        $('.piece').each(function(i,v){
            row_pcs+= +($(v).val()??0);
        });
        $('.other').each(function(i,v){
            row_othr+= +($(v).val()??0);
        });
        $('.ttl').each(function(i,v){
            row_ttl+= +($(v).val()??0);
            if($(v).val()!=""){
				if($(document).find('.item').eq(i).val()!=""){
					count++;
				}
                //count++;
            }
        });
        $("#list_item").val(count+" Item");
        $("#list_piece").val(row_pcs);
        $("#list_other").val(row_othr);
        $("#list_total").val(row_ttl);
	}
</script>
