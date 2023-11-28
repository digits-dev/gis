{{-- Please always check the current plugins, css, script in content.blade.php--}}

{{-- Extend the dashboard layout --}}
@extends('pos-frontend.components.content')

{{-- Your Plugins --}}
@section('plugins')
@endsection

{{-- Your CSS --}}
@section('css')
<style>
    #myTable td{
        text-align: center;
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
</style>
@endsection

@section('cash-float')

<div class="cash-float-section" id="view_history_tab" hidden>
    <form method="POST">
        @csrf
        <div class="cash-float">
            <div class="cash-float-content">
                <form method="POST">
                    @csrf
                    <div class="cash-float-header bg-text-color d-flex-al-c text-color-w">
                        <i class="fa fa-circle-o m-right-10"></i><p class="fs-20 c-danger fw-bold text-color-w" id="float_type_description"></p>
                    </div>
                    <div class="eod-table m-top-20">
                        <table>
                            <thead>
                                <tr>
                                    <th>Mode of Payment</th>
                                    <th>Value</th>
                                    @foreach ($float_entries as $float_entry)
                                    <th>
                                        {{ $float_entry->description }}
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mode_of_payments as $mode_of_payment)
                                <tr>
                                    <td>{{ $mode_of_payment->payment_description }}</td>
                                    @for ($i=0; $i<count($float_entries)+1; $i++)
                                        @if ($i == 0 || $mode_of_payment->payment_description == 'CASH')
                                            @if ($i == 0)
                                                <td><input type="text" style="height: 100%;" name="cash_value_{{ $mode_of_payment->payment_description }}" class="cash_value_{{ $mode_of_payment->payment_custom_desc }}" readonly></td>
                                                {{-- <td><input type="text" style="height: 100%;" class="cash_value_{{ $mode_of_payment->payment_description }}" readonly onkeypress="inputIsNumber()" ></td> --}}
                                            @else
                                                <td><input type="text" style="height: 100%;" name="cash_value_{{ $float_entries[$i-1]->description }}" class="cash_value_{{ $float_entries[$i-1]->description }}" readonly></td>
                                                {{-- <td><input type="text" style="height: 100%;" class="cash_value_{{ $float_entries[$i-1]->description }}" onkeypress="inputIsNumber()"></td> --}}
                                            @endif
                                        @else
                                        <td><input type="text" disabled></td>
                                        @endif
                                    @endfor
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="eod-v-q">
                        <div class="d-flex-al-c">
                            <p class="max-w-75">Total Value</p>
                            <input type="text" class="input-design total_value" name="total_value" style="height: 35px; width:165px;" placeholder="Total value" readonly>
                            {{-- <input type="text" class="input-design total_value" placeholder="Total value" onkeypress="inputIsNumber()"> --}}
                        </div>
                        <div class="d-flex-al-c m-top-10">
                            <p class="max-w-75">Token qty</p>
                            <input type="text" class="input-design total_token" name="total_token" style="height: 35px; width:165px;" placeholder="Token qty" oninput="numberOnly(this);" readonly>
                            {{-- <input type="text" class="input-design" placeholder="Token qty" onkeypress="inputIsNumber()"> --}}
                        </div>
                    </div>
                    <div class="d-flex-jcc-col m-top-30 m-bottom-15">
                        <button class="bg-primary-c text-color-w fw-bold m-top-10 start-of-day" type="button" id="close_tab">Close Tab</button>
                    </div>
                </form>
            </div>
        </div>
    </form>
</div>
@endsection
{{-- Define the content to be included in the 'content' section --}}
@section('content')

<div class="responsive_table">
    <form action="/pos_float_history">
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
                <th>Action</th>
                <th>Ref#</th>
                <th>Token Qty</th>
                <th>Token Value</th>
                <th>Peso</th>
                <th>Float Type</th>
                <th>Store Location</th>
                <th>Entry Date</th>
                <th>Created by</th>
                <th>Created Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entries as $entry)
                <tr>
                    <td>
                        <a class="btn btn-details history_btn_view" href="{{ route('view_float_history', $entry->cash_float_histories_id) }}" data-entry-id="{{ $entry->cash_float_histories_id }}" ><i class="fa-solid fa-eye"></i></a>
                    </td>
                    <td>FH-{{ str_pad($entry->cash_float_histories_id, 8, "0", STR_PAD_LEFT) }}</td>
                    <td>{{ number_format($entry->token_qty) }}</td>
                    <td>PHP {{ number_format($entry->token_value, 2, '.', ',') }}</td>
                    <td>PHP {{ number_format($entry->cash_value, 2, '.', ',') }}</td>
                    <td>{{ $entry->description }}</td>
                    <td>{{ $entry->location_name }}</td>
                    <td>{{ $entry->entry_date }}</td>
                    <td>{{ $entry->name }}</td>
                    <td>{{ $entry->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Action</th>
                <th>Ref#</th>
                <th>Token Qty</th>
                <th>Token Value</th>
                <th>Peso</th>
                <th>Float Type</th>
                <th>Store Location</th>
                <th>Entry Date</th>
                <th>Created by</th>
                <th>Created Date</th>
            </tr>
        </tfoot>
    </table>
    {{ $entries->links() }}
</div>
@endsection

{{-- Your Script --}}
@section('script-js')
<script>
    
    $(document).ready(function() {
        $('#myTable').DataTable({
            "bPaginate": false,
            "bFilter": false,
            "bInfo": false,
        });
    });

    $('.history_btn_view').click(function(e){
        e.preventDefault();
        const entryId = $(this).data('entry-id');
        const url = $(this).attr('href');
        $.ajax({
            url:url,
            dataType: 'json',
            type: 'GET',
            success: function(response){
                console.log(response);
                // Iterate through the response.cash_float_history_lines array
                $.each(response.cash_float_history_lines, function(index, line) {
                    $('.cash_value_' + line.entry_description).val(line.line_qty);

                    if (line.entry_description === "TOKEN") {
                        $('.total_token').val(line.line_qty);
                    }
                });

                $.each(response.cash_float_history_lines, function(index, line) {
                    $('.cash_value_' + line.payment_custom_desc).val(line.line_value);

                });
                const float_type_description = response.cash_float_history.description;
                $('#float_type_description').text(float_type_description + ' OF THE DAY');
                // $('#float_type_description').css('color', (float_type_description == 'END' ? 'black' : ''));
                $('.total_value').val(response.cash_float_history.cash_value);
                $('#view_history_tab').attr('hidden', false);

            },
            error: function(error){
                console.log(error);
            }
        })

    });

    $('#close_tab').click(function(e){
        e.preventDefault();
        $('input[type="text"]').val('');
        $('#view_history_tab').attr('hidden', true);
    });

</script>
@endsection