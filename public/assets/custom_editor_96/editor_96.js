  // Function to replace textarea or text field with rich editor
  function replaceWithEditor($element) {
    var originalContent = $element.val(); 
	
    var px_ini = $element.attr('rows')??250;

	var height = $("html").css('font-size').replace('px','') * 15;
	
    var $editor = $('<div contenteditable="true" class="rich-editor" style="padding:5px;height:'+height+'px;"></div>').html(originalContent);
    
	var $editor_container = $('<div class="editor_container" ></div>');
	$editor_container.html($editor);
    // Insert the editor in place of the textarea
    //$element.replaceWith($editor);
	$element.hide();
	
    //$element.replaceWith($editor_container);
	
	$element.after($editor_container);
	
	$editor.keyup(function() {
      var updatedContent = $editor.html(); // Get the content from the editor
      // Set the content back to the original textarea (used for form submission or other purposes)
	  var htmlTagsOnly = updatedContent.replace(/<[^>]*>/g, '').trim() === '';
	  if(htmlTagsOnly){
		  alert('blank');
		  $element.val('');
	  }else{
		$element.val(updatedContent);
	  }
    });
	
    // Add blur event to sync content back to original textarea when editing is done
    /*$editor.blur(function() {
      var updatedContent = $editor.html(); // Get the content from the editor
      // Set the content back to the original textarea (used for form submission or other purposes)
      $element.val(updatedContent);
    });*/

    // Add toolbar controls (optional)
    $editor.before(`
      <div class="toolbar">
        <button class="toolbar-btn" id="bold-btn"><b>B</b></button>
        <button class="toolbar-btn" id="italic-btn"><i>I</i></button>
        <button class="toolbar-btn" id="underline-btn"><u>U</u></button>
        <select id="font-family">
          <option value="Arial">Arial</option>
          <option value="Courier New">Courier New</option>
          <option value="Georgia">Georgia</option>
        </select>
        <button class="toolbar-btn" id="font-size-plus-btn">A+</button>
		    <button class="toolbar-btn" id="font-size-minus-btn">A-</button>
        <!--<select id="font-size">
          <option value="14px">14px</option>
          <option value="18px">18px</option>
          <option value="24px">24px</option>
        </select>-->
        <button class="toolbar-btn" id="left-align-btn">Left</button>
        <button class="toolbar-btn" id="center-align-btn">Center</button>
        <button class="toolbar-btn" id="right-align-btn">Right</button>
        <button class="toolbar-btn" id="unordered-list-btn">• List</button>
        <button class="toolbar-btn" id="ordered-list-btn">1. List</button>
        <!--<button class="toolbar-btn" id="heading-btn">H1</button>-->
        
        <select id="heading-size">
          <option value="">&nbsp;&#8461;</option>
          <option value="h1">H1</option>
          <option value="h2">H2</option>
          <option value="h3">H3</option>
          <option value="h4">H4</option>
          <option value="h5">H5</option>
          <option value="h6">H6</option>
        </select>
      </div>
    `);
	
    var dflt_size = 1;
    // Add toolbar functionality to the editor
    $('#bold-btn').click(function() { document.execCommand('bold'); });
    $('#italic-btn').click(function() { document.execCommand('italic'); });
    $('#underline-btn').click(function() { document.execCommand('underline'); });
    $('#font-family').change(function() { document.execCommand('fontName', false, $(this).val()); });

    $("#font-size-plus-btn").click(function(){dflt_size++; document.execCommand('fontSize', false, dflt_size);  });
	$("#font-size-minus-btn").click(function(){if(dflt_size > 1){ dflt_size--; } document.execCommand('fontSize', false, dflt_size);  });

    $('#font-size').change(function() { document.execCommand('fontSize', false, $(this).val()); });
    $('#left-align-btn').click(function() { document.execCommand('justifyLeft'); });
    $('#center-align-btn').click(function() { document.execCommand('justifyCenter'); });
    $('#right-align-btn').click(function() { document.execCommand('justifyRight'); });
    $('#unordered-list-btn').click(function() { document.execCommand('insertUnorderedList'); });
    $('#ordered-list-btn').click(function() { document.execCommand('insertOrderedList'); });
    $('#heading-btn').click(function() { document.execCommand('formatBlock', false, 'h1'); });
    $('#heading-size').change(function() { var heading = ($(this).val()!="")?$(this).val():'p';document.execCommand('formatBlock',false,heading); });

    
	$('button.toolbar-btn').click(function(e){ e.preventDefault(); })
  }
