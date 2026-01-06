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
    
    $('.record-tab-btn').click(function(){
        $('.record-tab-btn').removeClass('active');
        $(this).addClass('active');
        loadrecord();
    });


    function getcustomer(input){
        var keyword = $(input).val()??false;
        $("#custo_ladger").attr('href','javascript:void(null);');
        $('[name="custo"]').val("");
        $('[name="type"]').val("");
        $('[name="girvi"]').val("");
        $("#data_area").html();
        $.get('{{ route("girvi.custo") }}','mode=default&raw=true&keyword='+keyword,function(response){
            var li  = '';
            if(response.record){
                var data = response.record;
                if(Object.keys(data).length > 0){
					$.each(data,function(i,v){
                    //$(Object.keys(data)).each(function(i,v){
                        var name = v.name??"NA";
                        var num = v.num??'NA';
                        var mob = v.mobile??'NA';
                        var id=v.id??0;
                        var stream = name+" - "+mob;
                        stream+= (v.girvi_id)?'( '+(v.girvi_id)+' )':'';
                        li+=`<li><a href="{{ url('vendors/girvi/custo') }}/`+id+`" data-target="`+stream+`" class="select_customer" data-input="`+v.id+`~`+v.type+`">`+stream+`</a></li>`;
                    });
                    $("#customerlist").empty().append(li);
                    $("#customerlist").addClass('active');
                    positionmenu('#customerlist','#name');
                }
            }else{
                $("#customerlist").removeClass('active');
                $("#customerlist").empty();
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
            if(response.old){
                if(response.old.length > 0){
                    var old_record = response.old;
                    $.each(old_record,function(oi,ov){
                        var items = ov.items;
                        if(items.length>0){
                            $.each(old_record,function(oi,ov){
                                old_girvi_tr +=`<tr>
                                                    <td>
                                                        <label for="record_`+ov.id+`" class="rec_num_label">
                                                        <input type="radio" class="record_select_radio" name="girvi_record" value="`+ov.id+`" id="record_`+ov.id+`">`+oi+`
                                                        </label>
                                                    </td>
                                                    <td>
                                                        GRV-
                                                    </td>
                                                    <td>
                                                        `+ov.entry_date+`
                                                    </td>
                                                    <td>`
                                                        +ov.interest_type+`<hr class="m-0">`+ov.interest_rate+
                                                    `</td>
                                                    <td>
                                                        `+ov.girvi_garnt+`
                                                    </td>
                                                    <td>`+interest_val+`</td>
                                                    <td>`+(ov.girvi_garnt+interest_val)+`</td>
                                                    <td>`+ov.girvy_return_date+`</td>
                                                    <td>Received !</td>
                                                </tr>`; 
                                old_girvi_principal+= ov.girvi_garnt;
                                old_girvi_interest+=interest_val
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
                            $.each(new_record,function(ci,cv){
                               new_girvi_tr +=`<tr>
                                                    <td>
                                                        <label for="record_`+cv.id+`" class="rec_num_label">
                                                        <input type="radio" class="record_select_radio d-none" name="girvi_record" value="`+cv.id+`" id="record_`+cv.id+`">`+(ci+1)+`
                                                        </label>
                                                    </td>
                                                    <td>
                                                        GRV-
                                                    </td>
                                                    <td>
                                                        `+cv.entry_date+`
                                                    </td>
                                                    <td>`
                                                        +cv.interest_type+`<hr class="m-0">`+cv.interest_rate+
                                                    `</td>
                                                    <td>
                                                        `+cv.girvi_garnt+`
                                                    </td>
                                                    <td>`+interest_val+`</td>
                                                    <td>`+(cv.girvi_garnt+interest_val)+`</td>
                                                    <td>`+cv.girvy_return_date+`</td>
                                                    <td>Received !</td>
                                                </tr>`; 
                                new_girvi_principal+= cv.girvi_garnt;
                                new_girvi_interest+=interest_val;
                            });
                        }
                    });
                }else{
                    new_girvi_tr = false;
                }
            }
            if(response.girvi){
                $("[name='girvi']").val(response.girvi.id);
                $("#custo_ladger").attr('href','#');
                $('.fetch_custo_name').html(response.girvi.custo_name);
                $('.fetch_custo_girvi_num').html("GRV-"+response.girvi.girvi_id);
            }
            loadrecord();
            loadpayrecord(response.new,response.girvi);
            loadoptionsrecord(response.new,response.girvi);
        });
    });

    $(document).on('change','input.record_select_radio',function(){
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
    });
    
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
        $("#data_area").html(loading_tr);
        let active_tab = $('.record-tab-btn.active').data('target');
        let data = window[active_tab + '_tr']; 
        let principal = window[active_tab + '_principal'];
        let interest = window[active_tab + '_interest'];
        $("#data_area").html((!data)?norecord_tr:data);
        $("#total_principal").html(principal+' ₹');
        $("#total_interest").html(interest+' ₹');
        $("#total_amount_sum").html((principal + +interest)+' ₹');
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

    $(document).on('input','.floatdigit',function(){
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
        }*/

        $(this).val(val);
    });
</script>