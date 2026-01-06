<script>
let fullZPL = "";

async function generateZplFromTags(stock_type = false) {
   
    let labelBlocks = [];
    const tagElements = $(document).find(".item_tag");
    const tag_type = $(document).find('#code_type').val();
    for (let i = 0; i < tagElements.length; i++) {
        const $tag = $(tagElements[i]);

		const name = $tag.find('.name').text();
        const gross = $tag.find('.gross').text();
        const less = $tag.find('.less').text();
        const net = $tag.find('.net').text();
        const id = $tag.find('.id_val').text();
        const code = $tag.find('.code_val').text();
        

        // Part 1: Front (QR + weight + gross/less/net) at X=110
        // Part 2: ID/code in middle (264px offset from start of part 1)
        // Part 3: Nothing

        /*const labelZPL = `^XA
                        ^PW800
                        ^LH0,0
                        ^FO110,10^XG${grfName},1,1^FS
                        ^FO160,25^A0N,20,20^FDGROSS: ${gross}^FS
                        ^FO160,50^A0N,20,20^FDLESS: ${less}^FS
                        ^FO160,75^A0N,20,20^FDNET: ${net}^FS
                        ^FO320,30^A0N,20,20^FDID: ${id}^FS
                        ^FO320,60^A0N,20,20^FDCODE: ${code}^FS
                        ^XZ`;*/
        /*const labelZPL = `^XA
                        ^PW800
                        ^LH0,0
                        ^FO0,10^XG${grfName},1,1^FS
                        ^FO150,25^A0N,20,20^FDGROSS-${gross}^FS
                        ^FO150,50^A0N,20,20^FDLESS-${less}^FS
                        ^FO150,75^A0N,20,20^FDNET-${net}^FS
                        ^FO310,30^A0N,20,20^FDID-${id}^FS
                        ^FO310,60^A0N,20,20^FDCODE-${code}^FS
                        ^XZ`;*/
        /*const labelZPL = `^XA
                            ^BY1,3,30
                            ^FO120,20
                            ^BCN,50,N,N,N
                            ^FD${code}^FS
                            ^FO120,75
                            ^A0N,22,22
                            ^FD${code}^FS
                            ^XZ`;*/
        var  labelZPL = '';   
        switch(tag_type){
            case 'qrcode':
				if((stock_type!=false && stock_type!='false') && stock_type=='stone'){
                    labelZPL =  `^XA
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
                    labelZPL =  `^XA
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
                /*labelZPL =  `^XA
                            ^FO100,5
                            ^BQN,2,3
                            ^FDLA,${code}^FS
                            ^FO170,25^A0N,20,20^FDGROSS: ${gross}^FS
                            ^FO170,50^A0N,20,20^FDLESS: ${less}^FS
                            ^FO170,75^A0N,20,20^FDNET: ${net}^FS
                            ^FO320,30^A0N,20,20^FDID: ${id}^FS
                            ^FO320,60^A0N,20,20^FDCODE: ${code}^FS
                            ^XZ`;*/
                break;
            case 'barcode':
                labelZPL =  `^XA
                            ^BY2,3,30
                            ^FO110,20
                            ^BCN,50,N,N,N
                            ^FD${code}^FS
                            ^FO120,75
                            ^A0N,22,22
                            ^FD${code}^FS
                            ^FO320,25^A0N,20,20^FDGROSS: ${gross}^FS
                            ^FO320,50^A0N,20,20^FDLESS: ${less}^FS
                            ^FO320,75^A0N,20,20^FDNET: ${net}^FS
                            ^XZ`;
                /*labelZPL =  `^XA
                            ^BY2,3,30
                            ^FO110,20
                            ^B3N,50,Y,N,N
                            ^FD${code}^FS
                            ^FO120,75
                            ^A0N,22,22
                            ^FD${code}^FS
                            ^FO320,25^A0N,20,20^FDGROSS: ${gross}^FS
                            ^FO320,50^A0N,20,20^FDLESS: ${less}^FS
                            ^FO320,75^A0N,20,20^FDNET: ${net}^FS
                            ^XZ`;*/
                break;
            default:
                break;
        }   
        labelBlocks.push(labelZPL);
    }
    return labelBlocks;
    //return allZplImages + labelBlocks.join("\n");
}

$($(document).find('#zebra_print')).click(async function(){
    var stock_type = $(this).data('stock');
    let fullZPL = await generateZplFromTags(stock_type);
    printLabel(fullZPL); // Use BrowserPrint or USB method
});

/*$(document).on('click', '#zebra_print', async function () {
	var stock_type = $(this).data('stock');
    fullZPL = await generateZplFromTags(stock_type);
    printLabel(fullZPL); // Use BrowserPrint or USB method
});*/
</script>
