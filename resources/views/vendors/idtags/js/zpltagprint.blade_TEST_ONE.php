<script>
    
    let fullZPL = false;
    async function generateZplFromTags() {
        function base64ImageToZPL(base64, grfName) {
            return new Promise((resolve, reject) => {
                const img = new Image();
                img.crossOrigin = "anonymous";
                img.onload = () => {
                    const canvas = document.createElement("canvas");
                    const ctx = canvas.getContext("2d");

                    canvas.width = img.width;
                    canvas.height = img.height;
                    ctx.drawImage(img, 0, 0);
                    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    const data = imageData.data;

                    const width = canvas.width;
                    const height = canvas.height;
                    const bytesPerRow = Math.ceil(width / 8);
                    const totalBytes = bytesPerRow * height;
                    let zplData = "";

                    for (let y = 0; y < height; y++) {
                        let row = "";
                        for (let x = 0; x < width; x += 8) {
                            let byte = 0;
                            for (let b = 0; b < 8; b++) {
                                const pixelX = x + b;
                                if (pixelX < width) {
                                    const i = (y * width + pixelX) * 4;
                                    const r = data[i], g = data[i + 1], b = data[i + 2];
                                    const gray = (r + g + b) / 3;
                                    const bit = gray < 128 ? 1 : 0;
                                    byte |= bit << (7 - b);
                                }
                            }
                            row += byte.toString(16).padStart(2, '0').toUpperCase();
                        }
                        zplData += row + "\n";
                    }

                    const header = `~DG${grfName},${totalBytes},${bytesPerRow},\n`;
                    resolve(header + zplData);
                };
                img.onerror = reject;
                img.src = base64;
            });
        }
        
        let allZplImages = "";
        let labelBlocks = [];
        
        const tagElements = $(document).find(".item_tag");
        for (let i = 0; i < tagElements.length; i++) {
            const tag = tagElements[i];
            const $tag = $(tag);
            const base64 = $tag.find("img.tag_image").attr("src");
            const gross = $tag.find('.gross').text();
            const less = $tag.find('.less').text();
            const net = $tag.find('.net').text();
            const id = $tag.find('.id_val').text();
            const code = $tag.find('.code_val').text();

            const name = $tag.find(".name").text();
            const price = $tag.find(".wght").text();

            const grfName = `QR${i}.GRF`;
            const zplImage = await base64ImageToZPL(base64, grfName);
            allZplImages += zplImage;

            // layout (adjust ^FO positions as per label size)
            const labelZPL = `^XA
                            ^FO110,25^XGQR${i}.GRF,1,1^FS
                            ^FO110,25^A0N,20,20^FDGROSS-${gross}^FS
                            ^FO110,50^A0N,20,20^FDLESS-${less}^FS
                            ^FO110,75^A0N,20,20^FDNET-${net}^FS
                            ^FO320,30^A0N,20,20^FDID-${id}^FS
                            ^FO320,60^A0N,20,20^FDCODE-${code}^FS
                            ^XZ`;
            labelBlocks.push(labelZPL);
        }

        //fullZPL = allZplImages + labelBlocks.join("\n");
        return allZplImages + labelBlocks.join("\n");
        //return labelBlocks;
        //console.log(labelBlocks); // 👈 send this to your printer

    }

    $(document).on('click','#zebra_print',async function(e){
        fullZPL = await generateZplFromTags();
        //console.log(fullZPL);
        printLabel(fullZPL);
    });
</script>