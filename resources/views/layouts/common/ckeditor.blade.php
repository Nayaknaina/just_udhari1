<script src="https://cdn.ckeditor.com/4.16.2/full/ckeditor.js"></script>
<script>
    $(document).ready(function() {
        // Loop through each element with class 'ckeditor'
        $('.ckeditor').each(function() {
            // Initialize CKEditor with custom toolbar and plugins
            CKEDITOR.replace(this, {
                extraPlugins: 'font,colorbutton', // Adding extra plugins for font and color
                toolbar: [
                    { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'Undo', 'Redo'] }, // Clipboard tools
                    { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] }, // Style tools
                    { name: 'colors', items: ['TextColor', 'BGColor'] }, // Color tools
                    { name: 'insert', items: ['Image', 'Table', 'HorizontalRule'] }, // Insert tools (removed 'insertImage' as it is redundant)
                    { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat'] }, // Basic styles
                    { name: 'paragraph', items: ['NumberedList', 'BulletedList', 'Blockquote'] }, // Paragraph tools
                    { name: 'links', items: ['Link', 'Unlink'] }, // Link tools
                    { name: 'tools', items: ['Maximize'] } // Other tools
                ]
            });
        });
    });

</script>
