@extends('layouts.admin.app')

@section('css')
    @include('layouts.theme.css.datatable')
    <style>
        .pagination { margin: 0; }
        .dataTables_info { float: left; padding-top: 10px; }
    </style>
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Contact Enquiries</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Contact Enquiries</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Messages (Newest First)</h3>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label>Show entries</label>
                        <select class="form-control entries">
                            <option value="10">10</option>
                            <option value="25" selected>25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Search</label>
                        <input type="text" class="form-control search" placeholder="Name, Email, Subject...">
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="CsTable1" class="table table-bordered table- w-100">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Date & Time</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Subject</th>
                                <th width="15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            <!-- Data loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
                <div class="col-12" id="paging">
                    </div>

                <div class="row mt-3">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="table-info"></div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div id="pagination-result" class="float-right"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- View Modal -->
<div class="modal fade" id="viewMessageModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Contact Message</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <p><strong>Name:</strong> <span id="modal-name"></span></p>
                <p><strong>Email:</strong> <span id="modal-email"></span></p>
                <p><strong>Phone:</strong> <span id="modal-phone"></span></p>
                <p><strong>Subject:</strong> <span id="modal-subject"></span></p>
                <p><strong>Date:</strong> <span id="modal-date"></span></p>
                <hr>
                <p><strong>Message:</strong></p>
                <div id="modal-message" style="background:#f8f9fa;padding:15px;border-radius:8px;white-space: pre-line;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
@include('layouts.theme.js.datatable')

<script>
    const route = "{{ route('contact.data') }}";
    var data = {
                entries: $(".entries").val(),
                search: $(".search").val(),
                page: new URLSearchParams(url.split('?')[1] || '').get('page') || 1
            };
    
    function loadData(url = route) {
        let page = 1;
        if (url.includes('page=')) {
            const match = url.match(/[\?&]page=(\d+)/);
            if (match) page = match[1];
        }
        $.ajax({
            url: url,
            type: "GET",
            data: {
                entries: $(".entries").val(),
                search: $(".search").val(),
                page:page
            },
            success: function(res) {
                $("#table-body").empty().html(res.html);
                $("#paging").empty().html(res.paging);
                //$("#table-info").text(`Showing 1 to ${$('.entries').val()} of ${res.total} entries`);
            },
            error: function(xhr) {
                console.error("AJAX Error:", xhr.responseText);
            }
        });
    }
    
         $(document).ready(function() {
            $('#CsTable1').DataTable({
                destroy: true,       
                paging: false,      
                searching: false,    
                info: false,         
                lengthChange: false, 
                autoWidth: false,
                columnDefs: [
                    { targets: [0,6], orderable: false } 
                ]
            });
        });
    // Initial load
    loadData();

     // Search & Entries change
    $(".entries, .search").on('change keyup', function() {
        loadData();
    });

    // Pagination click
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        loadData($(this).attr('href'));
    });

    // View Message
    $(document).on('click', '.view-btn', function() {
         console.log("View button clicked");
        let btn = $(this);
        let id = btn.data('id');
        let wasRead = btn.data('read') == '1';
         console.log("ID:", id, "Was Read:", wasRead);
        $("#modal-name").text($(this).data('name'));
        $("#modal-email").text($(this).data('email'));
        $("#modal-phone").text($(this).data('phone'));
        $("#modal-subject").text($(this).data('subject'));
        $("#modal-date").text($(this).data('date'));
        $("#modal-message").html($(this).data('message'));
        $("#viewMessageModal").modal('show');

        if (wasRead) {  console.log("Already read → no update needed"); return;}
        
        $.post("{{ route('contact.read', ':id') }}".replace(':id',id), {
        _token: "{{ csrf_token() }}"
        })  
        .done(function(response) {

            console.log("Mark as read SUCCESS", response);
            let row = btn.closest('tr');
            console.log("Row found:", row);
            row.removeClass('font-weight-bold text-dark table-info');
            row.find(' .badge-danger').remove();
            btn.data('read', '1');
        })
        .fail(function(error) {
            console.log("Mark as read FAILED", error);
        });
    });

    // Delete
    $(document).on('click', '.delete-btn', function() {
        if (!confirm("Delete this message permanently?")) return;

        $.ajax({
            url:  $(this).data('path'),
            type: "DELETE",
            data: { _token: "{{ csrf_token() }}" },
            success: function() {
                loadData();
                toastr.success("Deleted successfully");
            }
        });
    });

   
</script>
@endsection