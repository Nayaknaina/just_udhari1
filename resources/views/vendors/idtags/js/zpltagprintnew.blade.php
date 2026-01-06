<script src="{{ asset('main/printer/BrowserPrint-3.0.216.min.js') }}"></script>
<script>
    var selected_device;

    function connecttoprinter(again=false){
        $("#printer_con").show();
        $("#printer_yes").hide();
        $("#printer_no").hide();
        BrowserPrint.getLocalDevices(function(devices) {
            // Filter for printers
            const printers = devices.filter(device => device.deviceType === 'printer');
    
            if (printers.length > 0) {
                // Set the first printer as default (or let the user choose)
                selected_device = printers[0];
                if(again){
                    setTimeout(function() {
                        toastr.success('Reconnected To Printer : '+selected_device.name);
                        $("#reconnectprinter").hide()
                        $("#printer_con").hide();
                        $("#printer_yes").show();
                        $("#printer_no").hide();
                        $('#printer_reconnect').find('i').removeClass('fa-spin');
                    }, 5000);
                }
                console.log("Default printer set to: " + selected_device.name);
            } else {
                //alert("No printers found");
                $("#printer_yes").hide();
                $("#printer_con").hide();
                $("#printer_no").show();
                $("#reconnectprinter").show()
                toastr.error("No printers found !");
            }
        }, function(error) {
            $("#printer_yes").hide();
            $("#printer_con").hide();
            $("#printer_no").show();
            $("#reconnectprinter").find('i').removeClass('fa-spin');
            $("#reconnectprinter").show();
            toastr.error("Error finding printers: " + error);
            //alert("Error finding printers: " + error);
        }, 'printer'); // Only look for printers
    }

    connecttoprinter();

    function printLabel(zpl) {
        if(selected_device){
            selected_device.send(zpl, 
            function(success){
                console.log(success);
            }, 
            function(error) {
                toastr.error("Print error: " + error);
                toastr.info("Check if the Printer Online <br>Remove the USB & Reconnect !");
            });
        }else{
            toastr.error("No Connection to Printer !");
            $("#reconnectprinter").show();
            $("#printer_yes").hide();
            $("#printer_con").hide();
            $("#printer_no").show();
        }
    }

    $(document).on('click','#printer_reconnect',function(){
        connecttoprinter(true);
        $(this).find('i').addClass('fa-spin');
    });

    $('#printBtn').click(function() {
        var zpl = `^XA
                    ^FO100,30^A0N,30,30^FDName   : product name^FS
                    ^FO100,70^A0N,30,30^FDWeight : 11/10Grm^FS
                    ^FO300,30^A0N,30,30^FDID     : ytrytr655656^FS
                    ^FO300,70^A0N,30,30^FDCode   : ADSA4545^FS
                    ^XZ`;
		
        printLabel(zpl);
    });
    
//var fullZPL = "";

async function generateZplFromTags(stock_type = false) {
   
    //let labelBlocks = [];
    const tagElements = $(document).find(".item_tag");
    const tag_type = $(document).find('#code_type').val();
    
    var  labelZPL = ''; 
    for (let i = 0; i < tagElements.length; i++) {
        const $tag = $(tagElements[i]);

        const name = $tag.find('.name').text();
        const gross = $tag.find('.gross').text();
        const less = $tag.find('.less').text();
        const net = $tag.find('.net').text();
        const id = $tag.find('.id_val').text();
        const code = $tag.find('.code_val').text();
          
        switch(tag_type){
            case 'qrcode':
                if((stock_type!=false && stock_type!='false') && stock_type=='stone'){
                    labelZPL +=  `^XA
                                ^FO100,15
                                ^BQN,2,3
                                ^FDLA,${code}^FS
                                ^FO170,25^A0N,20,20^FDNm.-${name}^FS
                                ^FO170,50^A0N,20,20^FDWt.-${gross}^FS
                                ^FO170,75^A0N,20,20^FH^FDRs.-${less}^FS
                                ^FO320,30^A0N,20,20^FDID-${id}^FS
                                ^FO320,60^A0N,20,20^FDCODE-${code}^FS
                                ^XZ`;
                }else{
                    labelZPL +=  `^XA
                                ^FO100,15
                                ^BQN,2,3
                                ^FDLA,${code}^FS
                                ^FO170,25^A0N,20,20^FDG.-${gross}^FS
                                ^FO170,50^A0N,20,20^FDL.-${less}^FS
                                ^FO170,75^A0N,20,20^FDN.-${net}^FS
                                ^FO320,30^A0N,20,20^FDID-${id}^FS
                                ^FO320,60^A0N,20,20^FDCODE-${code}^FS
                                ^XZ`;
                }
                break;
            case 'barcode':
                labelZPL +=  `^XA
                            ^BY2,3,30
                            ^FO110,20
                            ^BCN,50,N,N,N
                            ^FD${code}^FS
                            ^FO120,75
                            ^A0N,22,22
                            ^FD${code}^FS
                            ^FO320,25^A0N,20,20^FDG.-${gross}^FS
                            ^FO320,50^A0N,20,20^FDL.-${less}^FS
                            ^FO320,75^A0N,20,20^FDN.-${net}^FS
                            ^XZ`;
                break;
            default:
                break;
        }   
    }
    return labelZPL;
    //return allZplImages + labelBlocks.join("\n");
}

$(document).on('click', '#zebra_print', async function () {
    var stock_type = $(this).data('stock');
    let fullZPL = await generateZplFromTags(stock_type);
	//alert(fullZPL);
    printLabel(fullZPL); // Use BrowserPrint or USB method
});
</script>
