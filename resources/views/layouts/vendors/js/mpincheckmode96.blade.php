<style>
    #blockingmodal{
        z-index:9999!important;
        background: #00000052;
    }
</style>
<script>
var data_element = "";
var data_action = "";

$('#mpincheckform').on('submit', function (e) {
    e.preventDefault();
    var formdata = $(this).serialize();
    var action = "{{ route('shopschemes.mpincheck') }}";
    $.post(action, formdata, function (response) {
        var res = JSON.parse(response);
        if (res[0]) {
            $("#blockingmodal").modal('hide');
            $(data_element).trigger(data_action);
        } else {
            $("#mpinerror").empty().append("Invalid MPIN !");
            $("#mpinerror").removeClass('d-none');
            toastr.error("Invalid MPIN !");
        }
    });
});
function launchmpinmodal() {
    $("#mpinerror").addClass('d-none');
    $('input[type="password"]').val("");
    $("#blockingmodal").modal();
}
</script>