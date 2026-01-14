 <script>
    $(document).on('change input','input,select',function(){
        $(this).removeClass('is-invalid');
    });
    
    
    var old_girvi_tr = new_girvi_tr = pay_girvi_tr = '';
    var new_girvi_principal = old_girvi_principal = new_girvi_interest = old_girvi_interest = new_girvi_sum = old_girvi_sum = 0;
    const loading_tr = '<tr><td colspan="9" class="text-center text-primary"><span><i class="fa fa-spinner fa-spin"></i> Loading Record !</span</td></tr>';
    const norecord_tr = '<tr><td colspan="9" class="text-center text-primary"><span> No Record !</span</td></tr>';
    $('.tab-btn').click(function(){
        $('.tab-btn').removeClass('active').prop('checked',false);
        $(this).addClass('active')
        let target = $(this).attr('id');
        $('.tab-panel').hide();
        $('#'+target+'-pane').show();
    });
    
    /*$('.record-tab-btn').click(function(){
        $('.record-tab-btn').removeClass('active');
        $(this).addClass('active');
        loadrecord();
    });*/

    $('.new_girvi_page_tab_switch').click(function(e){
        e.preventDefault();
        $('.new_girvi_page_tab').hide();
        $($(this).attr('href')).show();
        $('.new_girvi_page_tab_switch').removeClass('active');
        $(this).addClass('active');
        $('.new_girvi_page_tab_switch').parent('li').removeClass('active');
        $(this).parent('li').addClass('active');
        loadrecord();
    });


    // function getcustomer(input){
    //     var keyword = $(input).val()??false;
    //     $("#custo_ladger").attr('href','javascript:void(null);');
    //     $('[name="custo"]').val("");
    //     $('[name="type"]').val("");
    //     $('[name="girvi"]').val("");
    //     $("#data_area").html();
    //     $.get('{{ route("girvi.custo") }}','mode=default&raw=true&keyword='+keyword,function(response){
    //         var li  = '';
    //         if(response.record){
    //             var data = response.record;
    //             if(data.length > 0){
    //                 $(data).each(function(i,v){
    //                     var name = v.name??"NA";
    //                     var num = v.num??'NA';
    //                     var mob = v.mobile??'NA';
    //                     var id=v.id??0;
    //                     var stream = name+" - "+mob;
    //                     stream+= (v.girvi_id)?'( '+(v.girvi_id)+' )':'';
    //                     li+=`<li><a href="{{ url('vendors/girvi/custo') }}/`+id+`" data-target="`+stream+`" class="select_customer" data-input="`+v.id+`~`+v.type+`">`+stream+`</a></li>`;
    //                 });
    //                 $("#customerlist").empty().append(li);
    //                 $("#customerlist").addClass('active');
    //                 positionmenu('#customerlist','#name');
    //             }
    //         }else{
    //             $("#customerlist").removeClass('active');
    //             $("#customerlist").empty();
    //         }
    //     });
    // }
   let customerXHR = null;

function getcustomer(input) {
    let keyword = $(input).val().trim();

    
    if (!keyword) {
        $("#customerlist").removeClass('active').empty();
        return;
    }

    // abort previous request
    if (customerXHR) customerXHR.abort();

    $("#customerlist")
        .html(`<li class="loading">Searching...</li>`)
        .addClass('active');

    positionmenu('#customerlist', '#name');

    customerXHR = $.get(
        '{{ route("girvi.custo") }}',
        'mode=default&raw=true&keyword=' + encodeURIComponent(keyword),
        function (response) {

            let li = '';
            let records = [];

            // 🔥 normalize response (array OR object)
            if (response.record) {
                records = Array.isArray(response.record)
                    ? response.record
                    : Object.values(response.record);
            }

            // 🔥 process records safely
            records.forEach(function (v) {

                let name = (v.name && v.name.trim()) ? v.name : '';
                let mob  = (v.mobile && v.mobile.trim()) ? v.mobile : '';

                if (!name && !mob) return;

                let stream = [];
                if (name) stream.push(name);
                if (mob) stream.push(mob);
                if (v.girvi_id) stream.push(`(${v.girvi_id})`);

                li += `
                    <li>
                        <a href="{{ url('vendors/girvi/custo') }}/${v.id}"
                           class="select_customer"
                           data-input="${v.id}~${v.type}"
                           data-target="${stream.join(' - ')}">
                           ${stream.join(' - ')}
                        </a>
                    </li>`;
            });

            // no record
            if (!li) {
                li = `<li class="no-record">No customer found</li>`;
            }

            $("#customerlist").html(li);
        }
    ).fail(function (xhr, status) {
        if (status !== 'abort') {
            $("#customerlist").html(
                `<li class="no-record">Something went wrong</li>`
            );
        }
    });
}


    function positionmenu(container,input){
        const $menu = $(container);
        const $input = $(input);
        const input_height = $input.outerHeight();//Use To Specify Position From Top/Bottom
        const menu_height = $menu.outerHeight();
        const page_height = $(document).height();
        const from_top = $input.offset().top; 
        const from_bottom = $(document).height()-(from_top+input_height);
        
        switch(menu_height){
            case (menu_height/4<from_bottom):
                $menu.css({
                    top: input_height + 'px',
                });
                break;
            case (menu_height/4 < from_top):
                $menu.css({
                    bottom: input_height + 'px',
                });
                break;
            default :
                $menu.css({
                    top: input_height + 'px',
                });
                break;
        }
    }

    $(document).on('click','.select_customer',function(e){
        e.preventDefault();
        old_girvi_tr = new_girvi_tr = '';
        $("#customerlist").removeClass('active');
        $("#name").empty().val($(this).data('target'));
        var custo_data = $(this).data('input').split('~');
        $('[name="custo"]').empty().val(custo_data[0]);
        $('[name="type"]').empty().val(custo_data[1]);
        var trgt_path = $(this).attr('href');
        $("#data_area").html(loading_tr);
        $.get($(this).attr('href'),"type="+custo_data[1],function(response){
            // Store globally for other tabs (Return, etc.) to access late
            window.lastGirviData = response;
            
            $(document).trigger('customer_loaded', [response]);
            // Direct call to Return Tab Renderer to ensure visibility
            if(typeof window.renderReturnItems === 'function') {
                try {
                    window.renderReturnItems(response);
                } catch(err) {
                    console.error("Return Render Error:", err);
                }
            }

            if(response.old){
                if(response.old.length > 0){
                    var old_record = response.old;
                    $.each(old_record,function(oi,ov){
                        var items = ov.items;
                        if(items.length>0){
                            $.each(items,function(ii,iv){
                                let payable = iv.issue + iv.interest;
                                old_girvi_tr +=`<tr class="border-bottom-0">
                                                    <td class="font-weight-bold text-center">`+(ii+1)+`</td>
                                                    <td><span class="badge badge-pill badge-light border text-secondary px-2 py-1 font-weight-bold" style="font-size: 0.75rem;">GRV-`+iv.receipt+`</span></td>
                                                    <td class="font-weight-bold text-dark">`+iv.entry_date+`</td>
                                                    <td>
                                                        <span class="d-block font-xs text-muted text-uppercase">`+iv.interest_type+`</span>
                                                        <span class="font-weight-bold text-dark">`+iv.interest_rate+`%</span>
                                                    </td>
                                                    <td class="font-weight-bold text-dark text-right pr-4">`+iv.issue+` ₹</td>
                                                    <td class="text-danger font-weight-bold text-right pr-4">`+iv.interest+` ₹</td>
                                                    <td class="text-success font-weight-bold text-right pr-4" style="font-size: 0.95rem;">`+(payable)+` ₹</td>
                                                    <td class="text-muted small">`+ov.girvy_return_date+`</td>
                                                    <td class="text-center">
                                                        `+((iv.status=='1') 
                                                            ? '<span class="badge badge-warning text-white shadow-sm px-2 py-1">Recieved</span>' 
                                                            : '<span class="badge badge-success shadow-sm px-2 py-1">Returned</span>')+`
                                                    </td>
                                                    <td class="text-center">
                                                        <button class="btn btn-sm btn-light btn-circle shadow-sm text-primary" title="View Details" style="width: 30px; height: 30px; padding: 0;">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>`; 
                                                
                                old_girvi_principal+= iv.principal;
                                old_girvi_interest+= iv.interest;
                                old_girvi_sum = old_girvi_principal  + old_girvi_interest;
                            });
                        }
                    });
                }else{
                    old_girvi_tr = false;
                }
            }
            if(response.new){
                if(response.new.length > 0){
                    var new_record = response.new;
                    $.each(new_record,function(bi,bv){
                        var items = bv.items;
                        if(items.length > 0){
                            $.each(items,function(ci,cv){
                                let cv_issue = parseFloat(cv.issue) || 0;
                                let cv_interest = parseFloat(cv.interest) || 0;
                                let bv_period = parseFloat(bv.girvy_period) || 0;
                                let payable = (cv_interest * bv_period) + cv_issue;
                               new_girvi_tr +=`<tr class="border-bottom-0">
                                                    <td class="font-weight-bold text-center">`+(ci+1)+`</td>
                                                    <td><span class="badge badge-pill badge-light border text-secondary px-2 py-1 font-weight-bold" style="font-size: 0.75rem;">GRV-`+(cv.receipt||'') +`</span></td>
                                                    <td class="font-weight-bold text-dark">`+(cv.entry_date||'-')+`</td>
                                                    <td>
                                                        <span class="d-block font-xs text-muted text-uppercase">`+(cv.interest_type||'Month')+`</span>
                                                        <span class="font-weight-bold text-dark">`+(cv.interest_rate||0)+`%</span>
                                                    </td>
                                                    <td class="font-weight-bold text-dark text-right pr-4">`+cv_issue+` ₹</td>
                                                    <td class="text-danger font-weight-bold text-right pr-4">`+cv_interest+` ₹</td>
                                                    <td class="text-success font-weight-bold text-right pr-4" style="font-size: 0.95rem;">`+(payable)+` ₹</td>
                                                    <td class="text-muted small">`+bv.girvy_return_date+`</td>
                                                    <td class="text-center">
                                                        `+((cv.status=='1') 
                                                            ? '<span class="badge badge-warning text-white shadow-sm px-2 py-1">Recieved</span>' 
                                                            : '<span class="badge badge-success shadow-sm px-2 py-1">Returned</span>')+`
                                                    </td>
                                                    <td class="text-center">
                                                        <button class="btn btn-sm btn-light btn-circle shadow-sm text-primary" title="View Details" style="width: 30px; height: 30px; padding: 0;">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>`; 
                                new_girvi_principal += cv.principal;
                                new_girvi_interest +=cv.interest;
                                new_girvi_sum += payable;
                            });
                        }
                    });
                }else{
                    new_girvi_tr = false;
                }
            }
            if(response.girvi){
                $("[name='girvi']").val(response.girvi.id);
                $("#custo_ladger").attr('href',"{{ url('vendors/girvi/transactions') }}/"+response.girvi.id);
                $('.fetch_custo_name').html(response.girvi.custo_name);
                $('.fetch_custo_girvi_num').html("GRV-"+response.girvi.girvi_id);
            }
            loadrecord();
            loadpayrecord(response.new,response.girvi);
            loadoptionsrecord(response.new,response.girvi);
        });
    });

    /*$(document).on('change','input.record_select_radio',function(){
        $('label.rec_num_label').removeClass('active');
        var girvi_detail = '';
        if($(this).is(':checked')){
            $(this).parent('label').addClass('active');
            $("#int_pay_dataarea").html('<yt><td colspan="3" class="text-center text-danger"><i><span class="fa fa-spinner fa-spin"></span> Loading Item !</i></tr>');
            $.get('{{ route("girvi.batch") }}/'+$(this).val(),"",function(response){
                if(response.error){
                    toastr.error(response.error)
                }else{
                    var girvi = response.girvi;
                    if(girvi){
                        console.log(girvi);
                        $("#girvi_int_pay_default").hide();
                        $("#girvi_int_pay_block").show();
                        if(girvi.items.length > 0){
                            $("#int_item_count").html(girvi.item_count);
                            $("#int_girvi_amount").html(girvi.principle+' ₹');
                            $("#int_girvi_duration").html(girvi.girvy_period+" Month");
                            $("#int_girvi_return").html(girvi.girvy_return_date);
                            $("#int_girvi_int_type").html(girvi.interest_type);
                            $("#int_girvi_int_perc").html(girvi.interest_rate+" %");
                            $("#int_girvi_int_amnt").html(girvi.interest+" ₹");
                            let payable = (+girvi.principle + +girvi.interest)*girvi.girvy_period;
                            $("#int_girvi_payable").html(payable+' ₹');
                            $("#int_girvi_paid").html('0 ₹');
                            $("#int_girvi_remains").html('0 ₹');
                            $("#int_amount").val(girvi.interest);
                            $("#old_amount").val(girvi.old_amount);
                            let to_pay = (girvi.old??0) + girvi.interest;
                            let tr  = '';
                            $.each(girvi.items,function(ii,item){
                                    tr+=`<tr>
                                            <td>`+(ii+1)+`</td>
                                            <td>`+(item.detail)+`</td>
                                            <td>`+(item.value)+` ₹</td>
                                            </tr>`;
                            });
                            $("#int_pay_dataarea").html(tr);
                        }else{
                            toastr.error("Items Not Found !");
                        }
                    }else{
                        toastr.error("Invalid Record Selected !");
                    }
                }
            });
        }
    });*/
    
    $(document).on('keydown',function(e){
        const input_element = $(':focus');
        const list_vis = $("#customerlist").css('display');
        if(input_element.prop('name')=="name" && list_vis=='block'){
            if(event.key=='ArrowUp' || event.key=='ArrowDown'){
                var li_index = $("#customerlist li.hover").index();
                var li_count =  $("#customerlist li").length-1;
                $("#customerlist li").removeClass('hover');
                if(event.key=='ArrowUp'){
                    if(li_index!=-1){
                        $("#customerlist li").eq(li_index-1).addClass('hover');
                    }

                }
                if(event.key=='ArrowDown'){
                    if(li_index!=li_count){
                        $("#customerlist li").eq(li_index+1).addClass('hover');
                    }
                }
            }else{
                if(event.key=='Tab'){
                    $("#customerlist li.hover>a").trigger('click');
                }
            }
        }
    });
    
    function loadrecord(){
        $("#record_data_area").html(loading_tr);
        //let active_tab = $('.new_girvi_page_tab_switch.active').data('target');
        let active_tab = $('.record-tab-btn.active').data('target');
        let data = window[active_tab + '_tr']; 
        let principal = window[active_tab + '_principal'];
        let interest = window[active_tab + '_interest'];
        let payable = window[active_tab + '_sum'];
        $("#record_data_area").html((!data)?norecord_tr:data);
        $("#total_principal").html(principal+' ₹');
        $("#total_interest").html(interest+' ₹');
        $("#total_amount_sum").html((payable)+' ₹');
    }
    
    function loadpayrecord(record,girvi=false){
        pay_girvi_tr = '';
        $("#pay_data_area").html('<tr><td colspan="11" class="text-center text-primary"><span><i class="fa fa-spinner fa-spin"></i> Loading Content !</span></tr>');
        if(record.length>0){
            $.each(record,function(bi,bv){
                var items = bv.items;
                if(items.length > 0){
                    $.each(items,function(ri,rv){
                        let property = (rv.property!="")?JSON.parse(rv.property):false;
                        let gross = net = pure = fine = '-';
                        if(property){
                            gross = property.gross+' gm';
                            net = property.net+' gm';
                            pure = property.pure+' %';
                            fine = property.fine+' gm';
                        }

                        let principal = (rv.flip=='1')?rv.activeflip.post_p:rv.principal;
                        let interest = (rv.flip=='1')?rv.activeflip.post_i:rv.interest;
                        let tr_class = (rv.status=='0')?'success':'';
                        pay_girvi_tr+=`<tr class="pay_item_row `+tr_class+`">
                                        <td>`+(ri+1)+`</td>
                                        <td>`+(rv.category)+`<hr class="m-1">`+(rv.detail)+`</td>
                                        <td><img src="/`+(rv.image)+`"  class='img-responsive img-thumbnail' style='height:100px;width:auto;'></td>
                                        <td><b>GROSS : </b>`+(gross)+`<hr class="m-1"><b>NET : </b>`+(net)+`</td>
                                        <td><b>PURE : </b>`+(pure)+`<hr class="m-1"><b>FINE : </b>`+(fine)+`</td>
                                        <td class="text-info"><b>RATE : </b>`+(rv.rate)+` ₹/gm<hr class="m-1"><b>VALUE : </b>`+(rv.value)+` ₹</td>
                                        <td><b>TYPE : </b>`+(rv.interest_type)+`<hr class="m-1"><b>RATE : </b>`+(rv.interest_rate)+` %</td>
                                        <td class="text-success "><span class="item_principal">`+(principal)+`</span> ₹</td>
                                        <td class="text-success"><span class="item_interest">`+(interest)+`</span> ₹</td>
                                        <td>`;
                                        if(rv.status=='0'){
                                    pay_girvi_tr+= `<b class="text-success"><i>Returned !</i></b>`;
                                            if(rv.remark){
                                            pay_girvi_tr+=`<hr class="m-0"><i class="text-info">`+rv.remark+`</i>`;    
                                            }
                                        }else if(principal==0){
                                    pay_girvi_tr+= `<a href="{{ route('girvi.return') }}/`+rv.id+`" class="btn btn-sm btn-success return_item" title="Return !">&#10150;&#9736;</a>`;
                                        }else{
                                    pay_girvi_tr+=  `<label for="record_check_`+rv.id+`">
                                                <input type="checkbox" name="item[]" value="`+rv.id+`" id="record_check_`+rv.id+`" class="item_select">
                                            </label>`;
                                        }
                                        /*if(principal==0){
                                            if(rv.status=='0'){
                            pay_girvi_tr+= `<b class="text-success"><i>Returned !</i></b>`;
                                            }else{
                            pay_girvi_tr+= `<a href="{{ route('girvi.return') }}/`+rv.id+`" class="btn btn-sm btn-success return_item" title="Return !">&#10150;&#9736;</a>`;
                                        }}else{

                            pay_girvi_tr+=  `<label for="record_check_`+rv.id+`">
                                                <input type="checkbox" name="item[]" value="`+rv.id+`" id="record_check_`+rv.id+`" class="item_select">
                                            </label>`;
                                        }*/
                            pay_girvi_tr+= `</td>
                                        </tr>`;
                    });

                }
            });
            $("#pay_data_area").html(pay_girvi_tr);
        }else{
            $("#pay_data_area").html('<tr><td colspan="11" class="text-center"><span class="text-danger">No Record  !</span></tr>');
        }
        if(girvi){
            let old_principal = girvi.balance_principal;
            let old_interest = girvi.balance_interest;
            let p_design_class= (old_principal != 0)?((old_principal < 0)?'danger':'success'):'info';
            let i_design_class= (old_interest != 0)?((old_interest < 0)?'danger':'success'):'info';
            old_principal  = ((old_principal > 0)?'+':'')+old_principal;
            old_interest  = ((old_interest > 0)?'+':'')+old_interest;
            $("#int_old_principal,#int_old_interest").removeClass("border-danger border-success");
            $("#int_old_principal").addClass('border-'+p_design_class);
            $("#int_old_principal_value").addClass('text-'+p_design_class).html(old_principal+" ₹");
            $("#int_old_interest_value,#int_old_principal_value").removeClass('text-danger text-sucees');
            $("#int_old_interest").addClass('border-'+i_design_class);
            $("#int_old_interest_value").addClass('text-'+i_design_class).html(old_interest+" ₹");
        }
    }

    function loadoptionsrecord(record,girvi=false){
        var option_tr = '';
        $("#option_data_area").html('<tr><td colspan="9" class="text-center text-primary"><span><i class="fa fa-spinner fa-spin"></i> Loading Content !</span></tr>');
        if(record.length>0){
            $.each(record,function(ri,rv){
                let b_principal = (rv.flip=='1')?rv.activeflip.post_p:rv.principle;
                let b_interest = (rv.flip=='1')?rv.activeflip.post_i:rv.interest;
                option_tr+=`<tr>
                                <td>`+(ri+1)+`</td>
                                <td>`+rv.entry_date+`</td>
                                <td>GRV_B-`+rv.receipt+`</td>
                                <td><a href="#batch_item_`+rv.id+`" class="btn btn-sm btn-outline-secondary option_item_list_button"><b>`+rv.item_count+`</b><i class="fa fa-caret-down"></i></a></td>
                                <td>`;
                                if(rv.flip==1){
                        option_tr+=`<b>VALUE : </b>`+rv.activeflip.now_value+`₹ '<br>`+`<strike><small><b>Value : `+rv.girvi_value+` ₹</b></small></strike><hr class="m-0">
                                    <b>ISSUE : </b>`+rv.activeflip.post_p+`₹ '<br>`+`<strike><small><b>Issue : `+rv.girvi_issue+` ₹</b></small></strike>`;
                                }else{
                        option_tr+=`<b>VALUE : </b>`+rv.girvi_value+` ₹<hr class="m-0"><b>ISSUE : </b>`+rv.girvi_issue+` ₹`;
                                }
                        option_tr+=`</td>
                                <td>`+rv.interest_type+`<hr class="m-0">`+rv.interest_rate+`%</td>
                                <td>`+rv.girvy_period+`-Months<hr class="m-0"><b>ISSUE : </b>`+rv.girvy_issue_date+`<hr class="m-0"><b>RETURN : </b>`+rv.girvy_issue_date+`</td>
                                <td>
                                    <b>PRINCIPAL : </b>`+b_principal+` ₹<hr class="m-0">
                                    <b>INTEREST : </b>`+b_interest+` ₹
                                </td>
                                <td>
                                <ul style="list-style:none;" class="p-0 text-center">
                                    <li class="mb-1">
                                        <button type="button" class="btn btn-sm btn-outline-success girvioperationbtn" data-toggle="modal" data-target="#girvioperationmodal"  data-href="{{ route('girvi.operation') }}/extrapayment/`+rv.id+`" class="btn btn-sm btn-outline-success" title="Extra Girvi Payment !">Pay</button>
                                    </li>
                                    <li>
                                        <a  href="#batch_item_`+rv.id+`" class="btn btn-sm btn-outline-secondary option_item_list_button"><i class="fa fa-caret-down"></i></a>
                                    </li>
                                </ul>
                                </td>
                            </tr>`;
                var items = rv.items;
                if(items.length > 0){
                    option_tr+=`<tr class="child_table_tr option_item_tr" id="batch_item_`+rv.id+`" style="display:none;"><td colspan="9"><table class="table child_table mb-0"><thead><tr class="bg-light">
                                <th  class="text-dark">Sn.</th>
                                <th  class="text-dark">GIRVI</th>
                                <th  class="text-dark">IMAGE</th>
                                <th  class="text-dark">DETAIL</th>
                                <th  class="text-dark">PROPERTY</th>
                                <th  class="text-dark">VALUATION</th>
                                <th  class="text-dark">INTEREST</th>
                                <th  class="text-dark">REMARK</th>
                                <th class="text-dark">Replace</th>
                                </tr></thead><tbody class="child_table_tbody">`;
                    $.each(items,function(ii,iv){
                        let i_principal = (iv.flip=='1')?iv.activeflip.post_p:iv.principal;
                        let i_interest = (iv.flip=='1')?iv.activeflip.post_i:iv.interest;
                        let property = (iv.property!="")?JSON.parse(iv.property):false;
                        let gross = net = pure = fine = '-';
                        if(property){
                            gross = property.gross+' gm';
                            net = property.net+' gm';
                            pure = property.pure+' %';
                            fine = property.fine+' gm';
                        }
                        
                        let text_class = (iv.status=='0')?'text-success':'';
                        let tr_class = text_class.replace('text-','');
                        option_tr+=`<tr class="`+tr_class+`">
                                        <td class="`+text_class+`">`+(ii+1)+`</td>
                                        <td class="`+text_class+`">GRV_I-`+iv.receipt+`</td>
                                        <td class="`+text_class+`"><img src="/`+iv.image+`" class="img-responsive img-thumbnail" style="width:50px;height:auto;"></td>
                                        <td class="`+text_class+`">`+iv.category+`/`+iv.detail+`</td>
                                        <td class="`+text_class+`">
                                        <b>GROSS : </b>`+gross+`<hr class="m-0">
                                        <b>NET : </b>`+net+`<hr class="m-0">
                                        <b>PURE : </b>`+pure+` <hr class="m-0">
                                        <b>FNE : </b>`+fine+`<hr class="m-0">
                                        </td>
                                        <td class="`+text_class+`">
                                            <b>RATE : </b>`+iv.rate+` ₹/gm<hr class="m-0">
                                            <b>VALUE: </b>`+iv.value+` ₹<hr class="m-0">
                                            <b>PRINCIPAL: </b>`+i_principal+` ₹<hr class="m-0">
                                        </td>
                                        <td class="`+text_class+`">
                                            <b>TYPE : </b>`+iv.interest_type+`<hr class="m-0">
                                            <b>RATE: </b>`+iv.interest_rate+` %<hr class="m-0">
                                            <b>VALUE: </b>`+i_interest+` ₹<hr class="m-0">
                                        </td>
                                        <td class="text-info">`+iv.remark+`</td>`;
                                        if(iv.status=='0'){
                                option_tr+=`<td class="`+text_class+`"><b><i>Retrurned !</i></b></td>`;
                                        }else{
                                option_tr+= `<td class="`+text_class+`">
                                            <button type="button" class="btn btn-sm btn-outline-info girvioperationbtn" data-toggle="modal" data-target="#girvioperationmodal" title="Girvi Item Replace !" data-href="{{ route('girvi.operation') }}/itemreplace/`+iv.id+`" >Replace</a>
                                        </td>`;
                                        }
                                    `</tr>`;
                    });
                    option_tr+=`</tbody></table></td></tr>`;
                }
            });
            $("#option_data_area").html(option_tr);
        }else{
            $("#option_data_area").html('<tr><td colspan="9" class="text-center"><span class="text-danger"> No Record !</span></tr>');
        }
    }

    $(document).on('hidden.bs.modal', '.modal', function () {
        if ($('.modal.show').length > 0) {
            $('body').addClass('modal-open');
        }
    });

    function floatdigit(ele,limit=false){
        let val = $(ele).val()??ele.val();
        if(!/^\d*\.?\d*$/.test(val)){
            val = val.replace(/[^0-9.]/g, '');
            let parts = val.split('.');
            if (parts.length > 2) {
                val = parts[0] + '.' + parts.slice(1).join('');
            }
        }
        if(limit && val > limit){
            
        }else{
            $(ele).val(val)??ele.val(val);
        }
    }
    /*$(document).on('input','.floatdigit',function(){
        let val = $(this).val();

        // Allow only digits and one decimal point
        if (!/^\d*\.?\d*$/.test(val)) {
            // Remove invalid characters
            val = val.replace(/[^0-9.]/g, '');

            // Ensure only one decimal point is kept
            let parts = val.split('.');
            if (parts.length > 2) {
                val = parts[0] + '.' + parts.slice(1).join('');
            }
        }

        // Parse to float and check if it exceeds maxValue
        /*if (val && parseFloat(val) > maxValue) {
            // Simulate backspace: remove last character
            val = val.slice(0, -1);
        }*

        $(this).val(val);
    });*/
    function calculateItemRow(index) {
    let gross = parseFloat(document.getElementById('gross_' + index).value) || 0;
    let pure  = parseFloat(document.getElementById('pure_' + index).value) || 0;


    document.getElementById('net_' + index).value = gross.toFixed(2);


    let fine = (gross * pure) / 100;
    document.getElementById('fine_' + index).value = fine.toFixed(2);
}
</script>