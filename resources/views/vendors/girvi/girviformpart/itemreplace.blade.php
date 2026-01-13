<style>
    #item_replace_page_data{
        font-size:90%;
    }
    ul{
        list-style: none;
        padding: 0;
    }
    .inline-detail-ul{
        display: flex;
        flex-wrap: wrap; /* Prevent wrapping */
        justify-content: space-between; /* Optional: aligns first and last to edges */
        list-style: none;
        padding: 0;
        margin: 0;
        width: 100%;
    }
    .inline-detail-ul li {
        flex: 1; /* Equal width for all items */
        text-align: center;
        box-sizing: border-box;
        padding-top: 0.5rem;
    }
    .prop_ul > li{
        border:1px solid gray;
        border-radius:10px;
        padding: 0.1rem;
    }
    .valuation{
        margin-top:15px;
    }
    .valuation > .title{
        position: absolute;
        top: -10px;
        right: 0;
        left: 0; 
    }
    .valuation > .title >span{
        border:1px solid gray;
        border-radius:10px; 
        padding:0 5px;
        background-image: linear-gradient(to bottom,lightgray,white,lightgray);
    }
    .valuation > ul{
        border:1px solid green;
        border-radius:10px 10px 0 0;
    }
    li.bg-lighter{
        background: #f4f4f4;
    }
    .inline-detail-ul > li.title{
        position:absolute;
    }
    .input_block_ul{
        position: relative;
        padding-top: 15px;
        margin-top: 10px;
    }
    .input_block_ul > li{
        border-radius: unset;
    }
    .input_block_ul >li.title{
        position: absolute;
        top:-5px;
        border-radius: 0 15px 0 0px;
        border-bottom:unset; 
        font-weight:bold;
        padding:0 10px 0 5px;
    }
    .input_block_ul > li > b{
        font-weight:normal;
    }
    #ir_img_label_container:hover{
        background:#e5e5e5;
        #ir_img_label{
            color:#9f9f9f;
        }
    }
    #ir_img_label{
        color:lightgray;
    }
</style>
<div class="col-12" id="item_replace_page_data">
    <div class="row">
        <div class="col-md-4 text-center" style="align-content:center;border:1px solid lightgray;border-radius:10px;">
            @if($item->image && file_exists($item->image))
                <img src="{{ $item->image }}" class="img-thumbnail img-responsive">
            @else
                <b style="color:lightgray;">No Image !</b>
            @endif
        </div>
        <div class="col-md-8">
            <ul>
                <li class="text-center" style="border:1px solid lightgray;border-radius:5px;"><b> GRV_I-{{ $item->receipt }} </b></li>
                <li><b>TYPE : </b><span>{{ $item->category }}</span></li>
                <li><b>DETAIL : </b><span>{{ $item->detail }}</span></li>
            </ul>
        </div>
        <a href="javascript:void(null);" class="btn btn-sm btn-outline-dark" onclick="$('#propertu_value').toggle('slow');$(this).find('i').toggleClass('fa-caret-down fa-caret-up');" style="position:absolute;right:0;"><i class="fa fa-caret-down"></i></a>
    </div>
    <div class="row" id="propertu_value" style="display:none;">
        @if(in_array($item->category,['Gold','Silver']))
        @php 
            $property = json_decode($item->property,true);
        @endphp
        <div class="col-12 p-0">
            <ul class="inline-detail-ul prop_ul" >
                <li><b>GROSS</b><hr class="m-0"><span>{{ $property['gross'] }} gm</span></li>
                <li><b>NET</b><hr class="m-0"><span>{{ $property['net'] }} gm</span></li>
                <li><b>PURE</b><hr class="m-0"><span>{{ $property['pure'] }} %</span></li>
                <li><b>FINE</b><hr class="m-0"><span>{{ $property['fine'] }} gm</span></li>
            </ul>
        </div>
        @endif
        <div class="col-md-6 text-center p-0 valuation">
            <h6 class="title"><span>Valuation</span></h6>
            <ul class="inline-detail-ul">
                <li><b>RATE</b><hr class="m-0"><span>{{ $item->rate }} ₹</span></li>
                <li><b>VALUE</b><hr class="m-0"><span>{{ $item->value }} ₹</span></li>
                <li><b>ISSUE</b><hr class="m-0"><span>{{ $item->issue }} ₹</span></li>
            </ul>
        </div>
        <div class="col-md-6 text-center p-0 valuation">
            <h6 class="title"><span>Interest</span></h6>
            <ul class="p-0 inline-detail-ul">
                <li><b>TYPE</b><hr class="m-0"><span>{{ $item->interest_type }}</span></li>
                <li><b>RATE</b><hr class="m-0"><span>{{ $item->interest_rate }} %</span></li>
                <li><b>VALUE</b><hr class="m-0"><span>{{ $item->interest }} ₹</span></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center p-0">
            <ul class="p-0 inline-detail-ul">
                <li class="form-control h-auto p-0"><b>PRINCIPAL : </b><span>{{ ($item->flip==1)?$item->activeflip->post_p:$item->principal }} ₹</span></li>
                <li class="form-control h-auto p-0"><b>INTEREST : </b><span>{{ ($item->flip==1)?$item->activeflip->post_i:$item->interest }} ₹</span></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <hr class="col-12 m-1 p-0" style="border-top:1px dashed orange;">
        <form role="form" action="{{ route('girvi.operation') }}/itemreplace" id="girvi_op_item_replace"  outocomplete="off">
            <div class="row">
                @csrf
                <input type="hidden" name="ir_pre_item" value="{{ $item->id }}">
                <div class="col-md-4">
                    <label for="ir_image" class="form-control w-100 btn-roundhalf text-center" class="form-control" style="min-height:100px;height:auto;cursor:pointer;align-content:center;" id="ir_img_label_container">
                        <span id="ir_img_label">Select Image !</span>
                        <img src="" class="img-thumbnail img-responsive" id="ir_item_image_prev" style="display:none;">
                    </label>
                    <input type="file" name="ir_image" id="ir_image" style="display:none;">
                </div>
                <div class="col-md-8">
                    <div class="input-group mb-1">
                        <div class="input-group-prepend">
                            <label class="input-group-text p-0 px-1">Type</label>
                        </div>
                        <select name="ir_type" class="form-control form-control h-32px" value="" id="ir_type" onchange="if($(this).val()=='Gold' || $(this).val()=='Silver'){ $('#input_property').show();}else{ $('#input_property').hide();}">
                            <option value="">Select</option>
                        </select>
                        <div class="input-group-append">
                        <a href="javascript:void(null);" class="input-group-button  btn btn-sm btn-outline-dark w-auto" data-toggle="modal" data-target="#item_category_modal" ><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                    <div class="form-group mb-1">
                        <textarea class="form-control w-100 btn-roundhalf replace_input" id="ir_detail" name="ir_detail" placeholder="Enter Item Detail !"></textarea>
                    </div>
                </div>
                <div class="col-12 pt-2">
                    <ul class="inline-detail-ul prop_ul input_block_ul" id="input_property" style="display:none;">
                        <li class="title bg-lighter text-primary">Property</li>
                        <li class="bg-lighter">
                            <b>GROSS</b>
                            <hr class="m-0">
                            <div class="input-group position-relative  mb-0">
                                <input class="form-control h-32px btn-roundhalf  floatdigit text-center replace_input" type="text" placeholder="Gross Wt" name="ir_gross" id="ir_gross" value="">
                                <span class="gm-inside">gm</span>
                            </div>
                        </li>
                        <li class="bg-lighter">
                            <b>NET</b>
                            <hr class="m-0">
                            <div class="input-group position-relative  mb-0">
                                <input class="form-control h-32px btn-roundhalf  floatdigit text-center replace_input" type="text" placeholder="Net Wt." name="ir_net" id="ir_net" value="">
                                <span class="gm-inside">gm</span>
                            </div>
                        </li>
                        <li class="bg-lighter">
                            <b>PUTIRY %</b>
                            <hr class="m-0">
                            <div class="input-group position-relative  mb-0">
                                <input class="form-control h-32px btn-roundhalf  floatdigit text-center replace_input" type="text" placeholder="Purity %" name="ir_pure" id="ir_pure" value="">
                                <span class="gm-inside">%</span>
                            </div>
                        </li>
                        <li class="bg-lighter">
                            <b>FINE</b>
                            <hr class="m-0">
                            <div class="input-group position-relative  mb-0">
                                <input class="form-control h-32px btn-roundhalf  floatdigit text-center replace_input" type="text" placeholder="Fine Wt." name="ir_fine" id="ir_fine" value="">
                                <span class="gm-inside">gm</span>
                            </div>
                        </li>
                    </ul>
                    <ul class="inline-detail-ul prop_ul input_block_ul" id="input_valuation">
                        <li class="title bg-lighter text-primary">Valuation</li>
                        <li class="bg-lighter">
                            <b>Market Rate</b>
                            <hr class="m-0">
                            <div class="input-group position-relative  mb-0">
                                <input class="form-control h-32px btn-roundhalf  floatdigit text-center replace_input" type="text" placeholder="Rate" name="ir_m_rate" id="ir_m_rate" value="">
                                <span class="gm-inside">₹</span>
                            </div>
                        </li>
                        <li class="bg-lighter">
                            <b>Valuation</b>
                            <hr class="m-0">
                            <div class="input-group position-relative  mb-0">
                                <input class="form-control h-32px btn-roundhalf  floatdigit text-center replace_input" type="text" placeholder="Valuation" name="ir_value" id="ir_value" value="" readonly >
                                <span class="gm-inside">₹</span>
                            </div>
                        </li>
                    </ul>
                    <ul class="inline-detail-ul prop_ul input_block_ul" id="input_interest">
                        <li class="title bg-lighter text-primary">Interest</li>
                        
                        <li class="bg-lighter">
                            <b>Principal</b>
                            <hr class="m-0">
                            <div class="input-group position-relative  mb-0">
                                <input class="form-control h-32px btn-roundhalf  floatdigit text-center replace_input" type="text" placeholder="Issue Amount" name="ir_issue" id="ir_issue" value="" readonly>
                                <span class="gm-inside">₹</span>
                            </div>
                        </li>
                        <li class="bg-lighter">
                            <b>Interest</b>
                            <hr class="m-0">
                            <div class="input-group position-relative  mb-0">
                                <input class="form-control h-32px btn-roundhalf  floatdigit text-center replace_input" type="text" placeholder="Issue Amount" name="ir_int_value" id="ir_int_value" value="" readonly >
                                <span class="gm-inside">₹</span>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-12 text-center mt-2 pt-2" style="border-top:1px solid orange;">
                    <button type="submit" name="replace" value="item" class="btn btn-sm btn-success">
                        Replace
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    var ir_diff_perc = {{ $item->issue_diff_perc }};
    var ir_int_rate = {{ $item->interest_rate }};
    $("#ir_image").change(function(){
        const file = this.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#ir_item_image_prev').attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
            $("#ir_img_label").html("Image Selected !").hide();
        } else {
            $('#ir_item_image_prev').src("").hide();
            $("#ir_img_label").html("Select Image !").show(); // Hide preview if not an image
        }
    });

    
    $('.replace_input').on('input',function(){
        const ir_field_arr = ['ir_detail','ir_gross','ir_net','ir_pure','ir_fine','ir_m_rate','ir_value','ir_issue','ir_int_value'];
        const cat = $("#ir_type").val()??false;
        const self = $(this);
        if(cat){
            $.each(ir_field_arr,function(iri,irv){
                var hv_sm = (self.attr('id') == irv);
                var fld_val = $("#"+irv).val();
                let check = (irv == 'ir_gross' || irv == 'ir_net' || irv == 'ir_pure' || irv == 'ir_fine')?((cat =='Gold' || cat == 'Silver')?true:false):true;
                if(check){
                    if(hv_sm){
                        return false;
                    }else if(fld_val==""){
                            self.val('');
                            $('#'+irv).addClass('is-invalid');
                            $('#'+irv).focus();
                            return false;
                    }
                }
            });
        }else{
            $("#ir_type").focus();
            toastr.error("Select the Category !");
        }
    });

    $('#ir_pure').on('input',function(){
        const purity = $(this).val()??false;
        const net = $('#ir_net').val()??false;
        if(purity <= 100){
            if(purity && net){
                const fine = (net*purity)/100;
                $("#ir_fine").val(fine);
            }else{
                $("#ir_fine").val('');
            }
        }else{
            $("#ir_pure").val(purity.slice(0, -1));
            toastr.error("Purity Can't be greater than 100 !");
        }
    });

    $('#ir_fine').on('input',function(){
        $("#ir_pure").val('');
        const fine = $(this).val()??false;
        const net = $('#ir_net').val()??false;
        if(fine && net){
            const purity = (fine * 100)/net;
            if(purity > 100){
                $(this).val('');
                toastr.error("Purity Can't be greater Than 100 !");
            }else{
                $(this).val(purity);
            }
        }else{
            $(this).val('');
        }
    });

    $("#ir_m_rate").on('input',function(){
        const rate = $(this).val();
        const itm_cat = $("#ir_type").val()??false
        const first_digit = (itm_cat=='Gold' || itm_cat == 'Silver')?($("#ir_fine").val()??0):1;
        const value = Math.round((+first_digit * +rate).toFixed(3).replace(/\.?0+$/, ''));
        $("#ir_value").val(value);
        const principle = Math.round((((value * ir_diff_perc)/100)??0).toFixed(3).replace(/\.?0+$/, ''));
        $("#ir_issue").val(principle);
        alert((principle * ir_int_rate)/100);
        const interest = Math.round((((principle * ir_int_rate)/100)??0).toFixed(3).replace(/\.?0+$/, ''));
        $("#ir_int_value").val(interest);
    });

    $("#girvi_op_item_replace").submit(function(e){
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
                        field = $('[name="'+ei+'"]');
                            //$('[name="'+ei+'"]').addClass('is-invalid');
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
</script>


