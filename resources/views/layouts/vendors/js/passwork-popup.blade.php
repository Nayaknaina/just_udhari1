
<script>

    function showPasswordModal(intended_url) {

        $('#intended_url').val(intended_url);
        $('#passwordModal').modal('show') ;

    }

	$(document).on('click','.editButton',function(e){
        e.preventDefault() ;
        var intended_url = $(this).attr('href') ;
        showPasswordModal(intended_url) ;
    });

    $('.editButton').on('click', function(e) {
        e.preventDefault() ;
        var intended_url = $(this).attr('href') ;
        showPasswordModal(intended_url) ;

    }) ;

</script>

<script>

$('#confirmDeleteModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    //alert(button);
    var url = button.data('delete-url'); // Extract info from data-* attributes
    //alert(url);
    var form = $('#deleteForm');
    form.attr('action', url);
});

</script>
