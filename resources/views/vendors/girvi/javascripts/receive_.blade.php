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
        
        $(document).on('change',".category",function(){
            var val = $(this).find('option:selected').text()??false;
            var ind = $('.category').index($(this));
            var block = $('.item_row').eq(ind).find('.jewellery');
            if(val){
                if(val!='Gold' && val!='Silver'){
                    block.prop('readonly',true);
                    $('.item_row').eq(ind).find('span.jewellery_gm').hide();
                    $('.item_row').eq(ind).find('span.jewellery_rs').show();
                }else{
                    block.prop('readonly',false);
                    $('.item_row').eq(ind).find('span.jewellery_gm').show();
                    $('.item_row').eq(ind).find('span.jewellery_rs').hide();
                }
            }
            $(document).find('.detail').eq(ind).val('');
            $(document).find('.gross').eq(ind).val('');
            $(document).find('.net').eq(ind).val('');
            $(document).find('.pure').eq(ind).val('');
            $(document).find('.image').eq(ind).val('');
            $(document).find('.rate').eq(ind).val('');
            $(document).find('.value').eq(ind).val('');
        });

        $(document).on('click','#more_item',function(){
            var new_item = $("#main_item_row").clone();
            new_item.find('.form-control').val("");
            new_item.find('.jewellery').show();
            //new_item.find('#category_block').remove();
            new_item.attr('id','');
            var itm_btn = new_item.find('#more_item');
            itm_btn.removeClass('btn-dark').addClass('btn-outline-danger remove_item');
            itm_btn.html('&cross;');
            new_item.attr('id','');
            itm_btn.attr('id','');
            $("#item_table_body").append(new_item);
            itempanelcount();
            //new_item.insertAfter($(".girvi_item").eq(count));
        });
        $(document).on('click','.remove_item',function(){
            $(this).closest('tr.item_row').remove();
            itempanelcount();
        });
        function itempanelcount(){
            $('.item_count').each(function(i,v){
                $(this).html(i+1);
            });
        }

        function itemcalculation(){
            var ttl_val = 0;
            $('.item_row').each(function(gi,gv){
                const category = $('.category').eq(gi).find('option:selected').text()??false;
                const rate = $('.rate').eq(gi).val()??false;
                var value = 0;
                if(category=='Gold' || category=='Silver'){
                    const net = $('.net').eq(gi).val()??false;
                    const pure = $('.pure').eq(gi).val()??false;
                    var fine = 0; 
                    if(pure && net){
                        fine = (net * pure)/100;
                    }
                    value = Math.round((fine * $('.rate').eq(gi).val()).toFixed(3).replace(/\.?0+$/, ''));
                    $(".fine").eq(gi).val(fine);
                }else{
                    value = rate;
                }
                $('.value').eq(gi).val(value);
                ttl_val+= +value;
            });
            $("#valuation").val(ttl_val);
        }

        $(".pure").on('input',function(){
             $(this).removeClass('is-invalid');
            var pure = $(this).val()??false;
            if(pure){
                if(pure>100){
                   var nw =  pure.slice(0,-1);
                   $(this).val(nw);
                   toastr.error("Purity Can't be Greater than 100 !");
                   $(this).addClass('is-invalid');
                }
            }
        });
        
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
        
        $('.detail').on('input', function () {
            this.style.height = 'auto'; // Reset height
            this.style.height = (this.scrollHeight) + 'px'; // Set height to content
        });
        
        $("#grant").on('input',function(){
            const issue_amnt = $(this).val()??0;
            $("#principal_val").val(issue_amnt);
            var interest = $('#interest').val()??false;
            var tanure = $("#tenure").val()??false;
            if(tanure){
                if(interest){
                    let intr_amnt = ((issue_amnt * interest)/100) * tanure; 
                    $("#interest_val").val(intr_amnt);
                }
            }
        });

        const field_arr = ['category','detail','gross','net','pure','fine','rate','value'];
        $(document).on('input','.input_field',function(e){
            const ind = $('.item_row').index($(this).closest('.item_row'));
            const cat = $('.category').eq(ind).find('option:selected').text();
            const self = $(this);
            $.each(field_arr,function(fi,fv){
                $('.'+fv).eq(ind).removeClass('is-invalid')
                var hv_sm = self.hasClass(fv);
                var fld_val = $("."+fv).eq(ind).val();
                let check = (fv == 'net' || fv == 'gross' || fv == 'pure' || fv == 'fine')?((cat =='Gold' || cat == 'Silver')?true:false):true;
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
            itemcalculation();
        });
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