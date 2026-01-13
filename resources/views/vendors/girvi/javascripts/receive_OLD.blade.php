 <script>
        $.get('{{ route("girvi.cats") }}','cats=true',function(response){
            if(response.cats.length > 0){
                var option = '<option value="">Category? </option>';
                $.each(response.cats,function(i,v){
                    option+='<option value="'+v.name+'">'+v.name+'</option>';
                });
                $("#category").html(option);
            }else{
                $("#category").html('<option value="">No Data !</option>');
            }
        });
        
        $(document).on('change',"#category",function(){
            var val = $(this).find('option:selected').text()??false;
            if(val){
                $('.input_field:not(#category)').val('');
                if(val!='Gold' && val!='Silver'){
                    $('.jewellery').addClass('disabled');
                    $('.jewellery').find('.input_field').prop('readonly',true);
                    $('.other_item').hide();
                    $('.other_item').eq(1).show();
                }else{
                    $('.jewellery').removeClass('disabled');
                    $('.jewellery').find('.input_field').prop('readonly',false);
                    $('.other_item').hide();
                    $('.other_item').eq(0).show();
                }
            }
        });

        $(document).on('click','#more_item',function(){
            let fill = $("#value").val()??false;
            if(fill && fill!=0){
                const count = $("#pre_items > tr").length;
                if(count == 0){
                    $("#girvi_items").show()
                }
                var cat = $("#category").val();
                var info = $("#detail").val();
                var image = $("#uploadFile").val();
                var gross = $("#gross").val();
                var net = $("#net").val();
                var pur = $("#caret").val();
                var fine = $("#fine").val();
                var rate = $("#rate").val();
                var value = $("#value").val();
                //$("#valuation").val(+$("#valuation").val()+ +value);
                var disabled = (cat!="Gold" && cat!="Silver")?'disabled':'';
                var tr = `<tr>
                            <td class="text-center">
                                ${count+1}
                            </td>
                            <td class="text-center">
                                <input type="text" name="category[]" value="${cat}" class="category text-center" readonly>
                                <hr class="p-0 m-0">
                                <input type="text" name="detail[]" value="${info}" readonly class="text-center">
                            </td>  
                            <td class="weight  ${disabled}">
                                <input type="file" style="display:none;" name="" value="${cat}" readonly>
                                <input type="hidden"  name="gross[]" value="${gross}" readonly>
                                <input type="hidden"  name="net[]" value="${net}" readonly>
                                <input type="hidden"  name="caret[]" value="${net}" readonly>
                                <input type="text" name="fine[]" value="${fine}" class="text-center" readonly>
                                <span class="weight">Gm</span>
                            </td> 
                            <td class="amount">
                                <input type="text" name="rate[]" value="${rate}" class="text-center" readonly>
                                <span class="amount">/- ₹</span>
                            </td>  
                            <td >
                                <input type="text" name="value[]" value="${value}" class="value text-center" readonly>
                                <span class="amount">/- ₹</span>
                            </td>    
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-danger item_remove" style="padding: 0px 5px !important;">
                                    &cross;
                                </button>
                            </td>
                        </tr>`;
                $("#pre_items").append(tr);
                $('.input_field').val("");
            }
        });

       $(document).on('click','.item_remove',function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
        if($("#pre_items > tr").length==0){
           $("#girvi_items").hide();
        }else{
            $("#pre_items > tr").each(function(i,v){
                $(this).find('td').eq(0).text(i+1);
            });
        }
       });
       
       const field_arr = ['category','detail','gross','net','carat','fine','rate','value'];
        $(document).on('input','.input_field',function(e){
            const ind = $('.item_row').index($(this).closest('.item_row'));
            const cat = $('.category').eq(ind).find('option:selected').text();
            const self = $(this);
            $.each(field_arr,function(fi,fv){
                $('.'+fv).eq(ind).removeClass('is-invalid')
                var hv_sm = self.hasClass(fv);
                var fld_val = $("."+fv).eq(ind).val();
                let check = (fv == 'gross' || fv == 'net' || fv == 'carat' || fv == 'fine')?((cat =='Gold' || cat == 'Silver')?true:false):true;
                if(check){
                    if(hv_sm){
                        return false;
                    }else if(fld_val==""){
                            self.val('');
                            $('.'+fv).eq(ind).addClass('is-invalid');
                            $('.'+fv).eq(ind).focus();
                            return false;
                    }
                }
            });
            //itemcalculation();
        });

       $("#net").on('input',function(){
        var gross = $("#gross").val()??false;
        var net = $("#net").val()??false;
        if(+gross && +net){
            if(+net > +gross){
                var pre_net= net.slice(0,-1);
                $("#net").val(pre_net);
                toastr.error("Net Weight Can't Be Greater to Gross !");
            }
        }
       });

        $("#caret").on('input',function(){
             $(this).removeClass('is-invalid');
            var pure = $(this).val()??false;
            if(pure){
                if(pure>100){
                   var nw =  pure.slice(0,-1);
                   $(this).val(nw);
                   toastr.error("Purity Can't be Greater than 100 !");
                   $(this).addClass('is-invalid');
                }else{
                    let net = $("#net").val();
                    let fine = (net * pure)/100;
                    var fine_val = fine.toFixed(3).replace(/\.?0+$/, '');
                    $("#fine").val(fine_val);
                }
            }
        });
        
        $("#rate").on('input',function(){
            var rate = $(this).val()??false;
            if(rate){
                if($("#category").val()=='Gold' || $("#category").val()=='Silver' ){
                    var value = rate * $("#net").val()??false;
                    $("#value").val(value);
                }else{
                    $("#value").val(rate);
                }
                $("#value").trigger('input');
            }
        });

        $(document).on('input',"#value",function(){
            itemcalculation();
        });
        /*$("#value").on('input',function(){

        });*/

        $(document).on('click','.image_browse_label',function(){
            $(this).next('.image').trigger('click');
        });

        $(document).on('change','.image',function(){
            var file_name = "No file Selected !";
            const file = this.files[0]; // Only one file possible
            if (file) {
                file_name = file.name;
            }
            $(this).prev('label').html(file_name);
        });
    
        $('#grant').on('input',function(e){
            $("#grant , #main").removeClass('is-invalid');
            var main_str = $('#valuation').val()??false;
            var main = (main_str)?parseFloat(main_str):0;
            
            var grant_str = $(this).val()??false;
            var grant = (grant_str)?parseFloat(grant_str):0;
            if(main!=0){
                 if(main < grant){
                    toastr.error("Grant Cant Be Greater to The Valuation  !");
                    $(this).addClass('is-invalid');
                    let now_val = grant_str.slice(0, -1)
                    $(this).val(now_val);
                }
            }else{
                toastr.error("Please Provide Valuation !");
                $("#valuation").focus();
                $("#valuation").addClass('is-invalid');
                $(this).val("");
            }
        });

        $("#tenure").on('input',function(){
            var issue = $("#issue").val()??false;
            if(issue){
                var return_date ="";
                if($(this).val()!=""){
                    var return_date = generatenextdate(issue,$(this).val());
                }
                $("#return").val(return_date);
            }else{
                toastr.error("Please Enter the Issue Date !");
                $(this).val("");
                $("#issue").addClass('is-invalid').focus();
            }
            $("#interest,#interest_val,#payable").val("");
        });

        $('#interest').on('input',function(e){
            $(this).removeClass('is-invalid');
            var grant = $("#grant").val()??false;
            var tenure = $("#tenure").val()??false;
            var int = $(this).val()??false;
            var payable = 0;
            let int_tnr = 0;
            if(grant){
                if(tenure){
                    int_tnr = (grant*int)/100 * tenure;
                    payable = +int_tnr + +grant;
                }else{
                    toastr.error("Enter The Tanure !");
                    $("#tanure").addClass('is-invalid').focus();
                    $(this).val("");
                }
                $("#interest_val").val(int_tnr);
                $("#payable").val(payable);
            }else{
                toastr.error("Enter The GraNT !");
                $("#grant").addClass('is-invalid').focus();
                $(this).val("");
            }
        });
        
        $('#detail').on('input', function () {
            this.style.height = 'auto'; // Reset height
            this.style.height = (this.scrollHeight) + 'px'; // Set height to content
        });

        function itemcalculation(){
            var ttl_val = 0;
            $('.category').each(function(i,gv){
                var val = $(document).find(".value ").eq(i).val()??false;
                if(val){
                    ttl_val+= +val;
                }
            });
            $("#valuation").val(ttl_val);
            var issue_perc = $("#issueperc").val()??false;
            if(issue_perc){
                $("#grant").val((+ttl_val * +issue_perc)/100);
                $("#maxissue").val("");
            }
        }

        $("#issueperc").on('input',function(){
            var perc = $(this).val()??false;
            if(perc){
                var valuation = $("#valuation").val()??false;
                if(valuation){
                    var new_val = (valuation * perc)/100;
                    $("#grant").val(new_val);
                }
            }
        });

        $("#grant").on('input',function(){
            var issue = $(this).val()??false;
            if(issue){
                var valuation = $("#valuation").val()??false;
                if(valuation){
                    var iss_perc = (issue * 100)/valuation;
                    $("#issueperc").val(iss_perc);
                }
            }
        });

        $("#maxissue").on('input',function(){
            var valuation = $("#valuation").val()??false;
            if(valuation){
                var max = $(this).val()??false;
                if(+max){
                    if(+max > +valuation){
                        toastr.error("Max Can't Greater Than the Valuation Amount !");
                        $(this).val(max.slice(0,-1));
                    }
                }
            }else{
                $('#grant').addClass('is-invalid');
            }
        });

        function generatenextdate(startDateStr,monthsToAdd){
            
            let date = new Date(startDateStr);
            if (isNaN(date)) return null;

            let originalDay = date.getDate();
            
            date.setMonth(+date.getMonth() + +monthsToAdd);

            // Adjust if overflow (e.g. Jan 31 → Feb 29 or Feb 28)
            if (date.getDate() < originalDay) {
                date.setDate(0);
            }

            // Format YYYY-MM-DD
            let yyyy = date.getFullYear();
            let mm = String(date.getMonth() + 1).padStart(2, '0');
            let dd = String(date.getDate()).padStart(2, '0');

            return `${yyyy}-${mm}-${dd}`;
        }
    </script>

    <script>
        $(document).ready(function(){

            $(document).on('categoryformsubmit',function(respones){
                console.log(respones.detail);
                var data = respones.detail;
                if(data.errors){
                    var errors = data.errors;
                    $.each(errors,function(i,v){
                        $("input[name='"+i+"']").addClass('is-invalid');
                        var msg = "";
                        $.each(v,function(ei,ev){
                            if(msg !=""){
                                msg+='\n';
                                msg+=ev;
                            }else{
                                msg=ev;
                            }
                        });
                       toastr.error(msg);
                    });
                    $("[name='add'][value='itemcategory']").prop('disabled',false);
                    $("#process_wait").hide();
                }else if(data.error){
                    toastr.error(data.msg);
                }else{
                    success_sweettoatr('Item Category Addedd  !');
                    $('#process_wait').hide();
                    $("#category_modal_close").trigger('click');
                    $(".category").append('<option value="'+data.item.id+'">'+data.item.name+'</option>');
                    $(document).find('#ir_type').append('<option value="'+data.item.id+'">'+data.item.name+'</option>');
                }
            });
            
            $("#girvi_form").submit(function(e){
                e.preventDefault();
                $('.is-invalid').removeClass('is-invalid');
                var formData = new FormData(this);
                var path = $(this).attr('action');
                $.ajax({
                    url: path, // 
                    type: 'POST',
                    data: formData,
                    contentType: false,      
                    processData: false,      
                    success: function (response) {
                        if(response.errors){
                            let errors = response.errors;
                            let field = false;
                            $.each(errors,function(ei,ev){
                                if(/^[a-zA-Z_]+\.\d+$/.test(ei)){
                                    let ele = ei.split('.');
                                    field = $('[name="'+ele[0]+'[]"]').eq(ele[1]);
                                }else{
                                    field = $('[name="'+ei+'"]');
                                    //$('[name="'+ei+'"]').addClass('is-invalid');
                                }
                                field.addClass('is-invalid');
                                let msg = false;
                                $.each(ev,function(vi,vv){
                                    if(msg){
                                        msg+='\n';
                                    }else{
                                        msg = vv;
                                    }
                                    toastr.error(msg);
                                });
                            })
                        }else if(response.error){
                            toastr.error(response.error);
                        }else{
                            success_sweettoatr(response.success);
                            //location.reload();
                        }
                    },
                    error: function (xhr, status, error) {
                        alert('Server Error !.');
                    }
                });
            });
        });

    </script>