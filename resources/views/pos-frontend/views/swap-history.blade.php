{{-- Please always check the current plugins, css, script in content.blade.php--}}

{{-- Extend the dashboard layout --}}
@extends('pos-frontend.components.content')

{{-- Your Plugins --}}
@section('plugins')
@endsection

{{-- Your CSS --}}
@section('css')
<style>
    .display {
        display: none;
    }
    .btn {
        margin: 5px
    }
    .styled-table-void {
    margin: 25px 0;
    font-size: 0.9em;
    font-family: sans-serif;
    min-width: 400px;
    width: 60%;
    text-align: center;
    border-collapse: collapse;
    margin: 0 auto;
    font-weight: bold;
    }
    .styled-table-void td {
    border: 1px solid #838383;
    padding: 12px 15px;
    }
    .styled-table-void tr td:nth-of-type(odd) {
        color: #c02f2f;
    }
    .swal2-confirm {
        width: 95px;
    }
    .search-container {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-bottom: 8px;
        position: relative;
    }
    .search-bar {
        padding: 10px 8px;
        border: 1px solid gray;
        border-radius: 10px;
        width: 400px;
        align-self: flex-end;
    }
    .search-btn-container {
        position: absolute;
        top: 5px;
        right: 4px;
    }
    .search-btn {
        padding: 6px 10px;
        background-color: #dd2a40;
        color: white;
        border-radius: 10px;
        cursor: pointer;
    }
    .search-btn:hover {
        filter: brightness(110%);
    }
    .swal-table-container {
    display: flex;
    gap: 20px;
    }
    .swal-container2 {
    width: 70%;
    }

</style>
@endsection

{{-- Define the content to be included in the 'content' section --}}
@section('content')

<div class="responsive_table">
    {{-- <form action="/pos_swap_history">
        <div class="relative border-2 border-gray-100 m-4 rounded-lg">
            <div class="absolute top-4 left-3">
                <i
                    class="fa fa-search text-gray-400 z-20 hover:text-gray-500"
                ></i>
            </div>
            <input
                type="text"
                name="search"
                class="h-14 w-full pl-10 pr-20 rounded-lg z-0 focus:shadow focus:outline-none"
                placeholder="Search Laravel Gigs..."
            />
            <div class="absolute top-2 right-2">
                <button
                    type="submit"
                    class="h-10 w-20 text-white rounded-lg bg-red-500 hover:bg-red-600"
                >
                    Search
                </button>
            </div>
        </div>
    </form> --}}
    <form action="/pos_swap_history">
        <div class="search-container">
            <input
                class="search-bar"
                autofocus
                type="text"
                name="search"
                placeholder="Search"
            />
            <div class="search-btn-container">
                <button
                class="search-btn"
                    type="submit"
                >
                    Search
                </button>
            </div>
        </div>
    </form>
    <table id="myTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th style="text-align: center">Action</th>
                <th>Ref#</th>
                <th>Value</th>
                <th>Token</th>
                <th>Type</th>
                <th>Mode of Payments</th>
                <th>Location</th>
                <th>Payment Reference</th>
                <th>Created by</th>
                <th>Created Date</th>
                <th>Updated by</th>
                <th>Updated Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($swap_histories as $swap_history)
                <tr>
                    <th><a class="btn btn-details" href="/pos_swap_history/{{ $swap_history->id }}""><i class="fa-solid fa-eye"></i></a>
                        <a class="btn btn-void ajax-request-icon" data-id="{{ $swap_history}}" href="">
                            @if ($swap_history->status != "VOID" && date('Y-m-d', strtotime($swap_history->created_at)) == date('Y-m-d'))
                                @if (in_array(auth()->user()->id_cms_privileges, [1,5,15]))
                                    <i class="fa-solid fa-x"></i>
                                @endif
                            @endif
                        </a>
                    </th>
                   <th>{{ $swap_history->reference_number }}</th>
                   <th>{{ number_format($swap_history->total_value, 2,'.',',') }}</th>
                   <th>{{ number_format($swap_history->token_value) }}</th>
                   <th>{{ $swap_history->type_id }}</th>
                   <th>{{ $swap_history->mod_description }}</th>
                   <th>{{ $swap_history->location_name}}</th>

                    @if ( $swap_history->mod_description == "CASH")
                    <th></th>
                    @else
                    <th>{{ $swap_history->payment_reference}}</th>
                    @endif

                   <th>{{ $swap_history->created_by }}</th>
                   <th>{{ $swap_history->created_at }}</th>
                   <th>{{ $swap_history->updated_by }}</th>
                   <th>{{ $swap_history->updated_at }}</th>
                   <th>{{ $swap_history->status }}</th>
                </tr>
                @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th style="text-align: center">Action</th>
                <th>Ref#</th>
                <th>Value</th>
                <th>Token</th>
                <th>Type</th>
                <th>Mode of Payments</th>
                <th>Location</th>
                <th>Payment Reference</th>
                <th>Created by</th>
                <th>Created Date</th>
                <th>Updated by</th>
                <th>Updated Date</th>
                <th>Status</th>
            </tr>
        </tfoot>
    </table>
    {{ $swap_histories->links() }}
</div>
@endsection

{{-- Your Script --}}
@section('script-js')
<script src="{{ asset('plugins/sweetalert.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(function () {
        $('#myTable').DataTable({
            "order": [[9, 'desc']],
            "bPaginate": false,
            "bFilter": false,
            "bInfo": false,
        });
    })

    $(document).ready(function() {
        $(".display").fadeIn(1000);
    });

    $(document).ready(function () {

    $('.ajax-request-icon').off('click');

     $(document).on('click','.ajax-request-icon', function (e) {
        e.preventDefault(); // Prevent the default behavior of the link
        let current_id = $(this).attr('data-id');
        let swap_history_object = $.parseJSON(current_id);
        $.ajax({
                    type: 'GET', 
                    url: 'pos_swap_history/getDetails/'+swap_history_object.id,
                    data: { },
                success: function (res) {
                    const data = JSON.parse(res);
                    console.log(data)
                    Swal.fire({
                title: "Are you sure you want to void this transaction? ",
                icon: 'warning',
                reverseButtons: true,
                customClass: ((data.defective_returns.length != 0 || data.addons.length != 0) ? 'swal-container2' : ''),
                allowOutsideClick: false,
                html: 
                '<div class="swal-table-container">'+
                '<table class="styled-table-void">' +
                          '<tr><td>Reference Number</td><td>'+ data.swap_histories.reference_number +'</td></tr>' +
                          '<tr><td>Value</td><td>'+ data.swap_histories.total_value+'</td></tr>' +
                          '<tr><td>Mode of Payments</td><td>'+ data.swap_histories.payment_description+'</td></tr>' +
                          '<tr><td>Token</td><td>'+ (data.swap_histories.token_value ? data.swap_histories.token_value : '0')+'</td></tr>' +
                          '<tr><td style="width: 70%">Description</td><td>Quantity</td> </tr>' +
                          '</table>' +
                            (data.addons.length != 0 ? 
                            '<table class="styled-table-void">'+
                            '<tr><td colspan="2">Addons</td></tr>' +
                            '<tr><td style="width: 70%">Description</td><td>Quantity</td> </tr>' +
                            data.addons.map(item => `<tr><td>${item.description}</td><td>${item.qty}</td></tr>`).join('') +
                            '</table>' : '') +
                            (data.defective_returns.length != 0 ? 
                            '<table class="styled-table-void">'+
                            '<tr><td colspan="2">Defective Return</td></tr>' +
                            '<tr><td style="width: 70%">Jan Number</td><td>Quantity</td> </tr>' +
                            data.defective_returns.map(item => `<tr><td>${item.digits_code}</td><td>${item.qty}</td></tr>`).join('') +
                            '</table>' : '') +
                      '</div>',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                denyButtonText: `Don't save`,
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                    type: 'GET', 
                    url: 'pos_swap_history/edit/'+swap_history_object.id,
                    data: { },
                success: function (res) {
                    const data = JSON.parse(res);
                    console.log(data)
                    if(data.message == 'success'){
                     Swal.fire({
                        icon: 'success',
                        title: 'Void Successfully',
                        confirmButtonText: 'Ok',
                        }).then((result) => {
                            location.reload();
                        })
                    }
                    else{
                        Swal.fire(
                            {
                        icon: 'error',
                        title: data.message,
                        confirmButtonText: 'Ok',
                        }
                        )
                    }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                    });
                }
                })
                    },
                    error: function(err) {
                        console.log(err);
                    }
                    });
                    return;

     
        
    });
});
</script>
@endsection