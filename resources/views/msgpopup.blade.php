<style>
    #msgpopupmodal {
    display: none; /* Hidden by default */
    position: fixed; 
    z-index: 1; 
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4); /* Black with opacity */
}

/* Modal content */
.msgpopupmodal_modal-content {
    background-color: #fff;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 300px;
    border-radius: 5px;
    text-align: center;
    position:relative;
}

/* Close button */
#msgpopupmodalclose {
    color: #aaa;
    /* float: right; */
    font-size: 20px;
    /* font-weight: bold; */
    cursor: pointer;
    position:absolute;
    border: 1px solid pink;
    color: pink;
    right: -5px;
    top: -5px;
    border-radius:50%;
    padding:0 10px;
    background:white;
    opacity: 1;
	font-weight:bold;
}
#msgpopupmodalclose:hover{
    border:red;
    background:red;
    color:white;
    opacity: 1;
}
</style>
<div id="msgpopupmodal" class="modal">
    <div class="msgpopupmodal_modal-content">
        <span id="msgpopupmodalclose">&times;</span>
        <p id="msgpopupmodalmessage">This is your popup message!</p>
    </div>
</div>
<script>
    // Get elements
const modal = document.getElementById("msgpopupmodal");
const messageElement = document.getElementById("msgpopupmodalmessage");
const span = document.getElementById("msgpopupmodalclose");

// Show the modal
function showpopupmessage(message) {
    messageElement.textContent = message; // Set the message
    modal.style.display = "block";        // Show the modal
}

// Close the modal when 'x' is clicked
span.onclick = function() {
    modal.style.display = "none";
}

// Close the modal when clicking outside of it
window.onclick = function(event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
}
</script>