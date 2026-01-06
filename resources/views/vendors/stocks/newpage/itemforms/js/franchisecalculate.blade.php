@php 
    $edit = $edit??false;
@endphp
<script>
    $(document).on('input.frnch','.tag',function(){
        const index = itemcheck($(this));
    });

    $(document).on('input.frnch','.remark',function(){
        itemcheck($(this));
    });
    
    $(document).on('input.frnch','.piece',function(){
        const index =  itemcheck($(this));
        if($('.piece').val() == ''){
            toastr.error("Enter the Pieces !");
            $('.piece').eq(index).addClass('is-invalid').focus();
            return false;
        }else{
            $('.piece').eq(index).val($('.piece').eq(index).val().replace(/\D/g, ''));
        }
        calculateitemsum(index);
    });

    
    $(document).on('input.frnch','.gross',function(){
        const index = itemcheck($(this));
        const less = $(document).find('.less').eq(index).val()??0;
        const gross = $(this).val()??0;
        var net = null;
        if(gross!=0 && gross!=""){
            net = gross - less;
            $(document).find('.net').eq(index).val(net);
        }else{
            $(document).find('.less').eq(index).val('');
            $(document).find('.net').eq(index).val('');
        }
        calculateitemsum(index);
    });

    $(document).on('input.frnch','.less',function(){
        const index = itemcheck($(this));
        const gross = $(document).find('.gross').eq(index).val()??0;
        const less = $(document).find('.less').eq(index).val()??0
        if(gross!=0 && gross!=""){
            if(+less >= +gross){
                toastr.error("Less can't be Equal to Gross !");
                $(document).find('.less').eq(index).val($(this).val().slice(0,-1));
                less = $(document).find('.less').eq(index).val()
            }
            const net = gross - less;
            $(document).find('.net').eq(index).val(net);
        }else{
            toastr.error("Gross Required !");
            $(this).val($(this).val().slice(0,-1))
            $(document).find('.gross').eq(index).focus();
        }
        calculateitemsum(index);
    });

    $(document).on('input.frnch','.net',function(){
        const index = itemcheck($(this));
        const gross = $(document).find('.gross').eq(index).val()??0;
        var net = $(this).val()??0;
        if(gross!=0 && gross!=""){
            if(+net > +gross){
                $(document).find('.net').eq(index).val(net.slice(0,-1));
                net = $(document).find('.net').eq(index).val();
            }else{
                $(document).find('.net').eq(index).val(net);
            }
            const less = +gross - +net;
            $(document).find('.less').eq(index).val(less);
        }else{
            $(document).find('.net').eq(index).val(net.slice(0,-1));
        }
        calculateitemsum(index);
    });

    $(document).on('input.frnch','.rate',function(){
        const index =  itemcheck($(this));
        if($('.rate').val() != ''){
            var piece = $(document).find('.piece').eq(index).val()??0;
            if(piece==0){
                $(document).find('.piece').eq(index).val(1);
            }
        }else{
             toastr.error("Enter the Rate !");
            $('.rate').eq(index).addClass('is-invalid').focus();
            return false;
        }
        calculateitemsum(index);
    });

    $(document).on('input.frnch','.other',function(){
        const index =  itemcheck($(this));
        
        calculateitemsum(index);
    });

    $(document).on('input.frnch','.disc',function(){
        const index =  itemcheck($(this));
        calculateitemsum(index);
    });

    $(document).on('change.frnch','.discunit',function(){
        const index =  itemcheck($(this));
        calculateitemsum(index);
    });

    $(document).on('change.frnch','.image',function(){
        const index =  itemcheck($(this));
    });

    function itemcheck(element){
        const index = $('tr.item_tr').index(element.closest('tr.item_tr'));
        //alert(index);
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
        const gross = $('.gross').eq(index).val()??0;
        const net = $('.net').eq(index).val()??0;
        const piece = $('.piece').eq(index).val()??0;
        const rate = $('.rate').eq(index).val()??0;
        const other = $('.other').eq(index).val()??0;
        let disc = $('.disc').eq(index).val()??false;
        const unit = $('.discunit').eq(index).val()??false;
        if(gross=='' || gross==0 || net==0 || net==''){
            return false;
        }
        if(piece == 0 && rate == 0){
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

    function calculatestocksum(){
        let count = 0;
        let row_ttl = row_pcs = row_gross = row_net = row_ele = row_othr = 0;
        $('.gross').each(function(i,v){
            row_gross+= +($(v).val()??0);
        });
        $('.net').each(function(i,v){
            row_net+= +($(v).val()??0);
        });
        $('.chrg').each(function(i,v){
            row_ele+= +($(v).val()??0);
        });
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
        $("#list_gross").val(row_gross);
        $("#list_net").val(row_net);
        $("#list_ele").val(row_ele);
        $("#list_other").val(row_othr);
        $("#list_total").val(row_ttl);
    }
</script>
