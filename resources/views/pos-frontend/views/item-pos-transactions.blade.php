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
    .btn-details{
        background-color: #3c8dbc;
        padding: 1px 5.5px;
        border-radius: 2px;
        color: #fff;
    }

    .btn-void{
        background-color: #dd4b39;
        padding: 1px 5.5px;
        border-radius: 2px;
        color: #fff;
        font-size: 14px;
    }

    .swal2-confirm-red {
        background-color: #dd4b39 !important; /* red */
        color: white !important;
        border: none;
    }
    .bg-success{
        background-color: #00a65a;
        padding: 3px 5px;
        color: #fff;
        border-radius: 3px;
        font-size: 12px;
    }
    .bg-void{
        background-color: #dd4b39;
        padding: 3px 5px;
        color: #fff;
        border-radius: 3px;
        font-size: 12px;
    }

</style>
@endsection

@section('content')

<div class="responsive_table">

    <form action="/item_pos_transactions">
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
    <table id="myTable">
        <thead>
            <tr>
                @foreach($table_header as $key => $header)
                    <th @if($key == 'action') style="text-align: center" @endif>
                        {{ $header }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($item_pos as $item)
                <tr>
                    {{-- View button --}}
                    <td style="display: flex;">
                        <a class="btn btn-details" href="/pos_swap_history/{{ $item['id'] }}""><i class="fa-solid fa-eye"></i></a>
                        @if($item['status'] != "VOID")
                            <a class="btn btn-void" data-id={{$item['id']}} id="btnVoid">
                                @if ($item['status'] != "VOID" && date('Y-m-d', strtotime($item['created_at'])) == date('Y-m-d'))
                                    @if (in_array(auth()->user()->id_cms_privileges, [1,5,15]))
                                        <i class="fa-solid fa-times-circle"></i>
                                    @endif
                                @endif
                            </a>
                        @endif
                    </td>

                    {{-- Loop through visible columns --}}
                    @foreach($item as $key => $value)
                        @if(!in_array($key, ['id']))
                            <td>
                                @if($key === 'status')
                                    <span class="{{$value === 'VOID' ? 'bg-void' : 'bg-success'}}">
                                        {{ strtoupper($value) }}
                                    </span>
                                @else
                                    {{ $value }}
                                @endif
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
          
        </tbody>
        <tfoot>
            <tr>
                @foreach($table_header as $key => $header)
                    <th @if($key == 'action') style="text-align: center" @endif>
                        {{ $header }}
                    </th>
                @endforeach
            </tr>
        </tfoot>
    </table>
    {{ $item_pos->links() }}
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
    });

    $(document).ready(function() {
        $(document).on('click', '#btnVoid', function (e) {
            e.preventDefault();
            const currentId = $(this).data('id');
            const itemId = JSON.parse(currentId);
            $.ajax({
                type: 'GET',
                url: `item_pos_transactions/getDetail/${itemId}`,
                data: {},
                success: function (res) {
                    const data = res;
                    let htmlContent = `
                        <div class="swal-table-container">
                            <table class="styled-table-void">
                                <tr><td>Reference Number</td><td>${data.items.reference_number}</td></tr>
                                <tr><td>Mode of Payments</td><td>${data.items.mode_of_payments.payment_description}</td></tr>
                                <tr><td>Total Value</td><td>${data.items.total_value}</td></tr>
                                 <tr><td>Change Value</td><td>${data.items.change_value}</td></tr>
                            </table>
                    `;

                    htmlContent += '</div>';

                    Swal.fire({
                        title: "Are you sure you want to void this transaction?",
                        icon: 'warning',
                        reverseButtons: true,
                        allowOutsideClick: false,
                        html: htmlContent,
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        denyButtonText: `Don't save`,
                        customClass: {
                            confirmButton: 'swal2-confirm-red' // custom class for confirm button
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'GET',
                                url: `item_pos_transactions/void/${itemId}`,
                                success: function (res) {
                                    const response = res;
                                    if (response.message === 'success') {
                                        Swal.fire({
                                            icon: response.type,
                                            title:  response.message,   
                                            confirmButtonText: 'Ok',
                                        }).then(() => {
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: response.message,
                                            confirmButtonText: 'Ok',
                                        });
                                    }
                                },
                                error: function (err) {
                                    console.error(err);
                                }
                            });
                        }
                    });
                },
                error: function (err) {
                    console.error(err);
                }
            });
        });

    });
</script>
@endsection