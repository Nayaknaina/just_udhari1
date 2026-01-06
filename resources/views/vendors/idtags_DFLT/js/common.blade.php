<script>
const loader_tr = '<tr><th class="text-center"><span class="text-primary"><i class="fa fa-spinner fa-spin"></i> Loading Stock !</span></th></tr>';
function data(){
    var url_add_on = "";
    var metal = $("#metal").val()??false;
    if(metal){
        url_add_on+="metal="+metal+"&";
    }
    var type = $("#type").val()??false;
    if(type){
        url_add_on+="type="+type+"&";
    }
    var code = $("#tag").val()??false;
    if(code){
        url_add_on+="code="+code+"&";
    }
    var key = $("#keyword").val()??false;
    if(key){
        url_add_on+="keyword="+key+"&";
    }
    if(url_add_on!=""){
        url_add_on = url_add_on.replace(/&$/, '');
        url_add_on = "?"+url_add_on;
    }
    let path = "{{ route('idtags.stock') }}"+url_add_on;
    let item_row = '';
    let num = 0;
    let ttl_gross = 0;
    let ttl_net = 0;
    
    $("#avail_stock").empty().append(loader_tr);
    $.get(path,'',function(response){
        if(response.path!=""){
            item_row+='<tr><th colspan="3" class="p-0"><ul class="stock_path d-flex m-0">'+response.path+'</ul></th></tr>';
        }
        if(response.data.length > 0){
            $.each(response.data,function(i,v){
                const code_value = v[code];
                let prop = JSON.parse(v.property);
                let gross = (prop)?prop.gross_weight:"";
                let net = (prop)?prop.net_weight:"";
                num = i + 1;
                item_row+= '<tr id="avail_'+code+'_'+code_value+'">';
                item_row+='<td class="sn">'+ num +'</td>';
                item_row+='<td>';
                item_row+='<ul>';
                item_row+='<li>NAME : '+v.product_name+'</li>';
                item_row+='<li>ID : '+v.product_code+'</li>';
                item_row+='</ul>';
                item_row+='</td>';
                item_row+='<td>';
                item_row+='<ul>';
                item_row+='<li>WIGHT : '+gross+"/"+net+'Gm</li>';
                item_row+='<li>CODE : '+code_value+'</li>';
                item_row+='</ul>';
                item_row+='</td>';
                item_row+='</tr>';
                ttl_gross+= +gross;
                ttl_net+= +net;
            });
            //$("#avail_stock").empty().append(item_row);
            $(".avl_count").empty().append(num);
            $('#avl_wght').empty().append(ttl_gross+"/"+ttl_net);
        }else{
            item_row += '<tr><th  class="text-center"><span class="text-primary"><i class="fa fa-info-circle"></i> No Stock !</span></th></tr>';
        }
        $("#avail_stock").empty().append(item_row);
    });
}
$(document).on('change','#tag',function(){
    data();
});
$(document).on('change','.jewellery_cat',function(){
    data();
});
$(document).on('input','#keyword',function(){
    data();
});

</script>