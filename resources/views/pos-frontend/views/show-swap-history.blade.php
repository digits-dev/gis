@extends('pos-frontend.components.content')


<style>
 .main-container {
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: "Open Sans", sans-serif;
 }
 .styled-table {
    background-color: #e1e3e6;
    border-collapse: collapse;
    margin: 25px 0;
    font-size: 0.9em;
    font-family: sans-serif;
    min-width: 400px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    width: 60%;
    text-align: center;
    display: none;
}
.styled-table thead {
    border-top-right-radius: 10px;
}
.styled-table thead tr {
    background-color: #c02f2f;
    color: #ffffff;
    text-align: left;
}
.styled-table th,
.styled-table td {
    padding: 12px 15px;
}
.styled-table tbody {
    background-color: white;
}
.styled-table tbody tr {
    border-bottom: 1px solid #dddddd;
}

.styled-table th {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    text-align: center
}

.styled-table tbody tr:nth-of-type(odd) {
    background-color: #f3f3f3;
    font-weight: bold;
    color: #c02f2f;
}

.styled-table tbody tr:last-of-type {
    border-bottom: 2px solid #c02f2f;
}

.styled-table tfoot {
    text-align: left;
    background-color: white;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
}
.styled-table tfoot td {
    text-align: left;
    background-color: white;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
}
.btn-back {
    padding: 10px 12px;
    border: 1px solid rgb(167, 167, 167);
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}
.btn-back:hover {
    background-color: #c9c9c9;
}
</style>
@section('content')

    <div class="main-container">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th colspan="3">Swap Token Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Reference Number</td>
                        <td>{{ $swap_histories->reference_number }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Value</td>
                        <td>{{number_format($swap_histories->total_value, 2,'.',',') }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Token</td>
                        <td>{{ number_format($swap_histories->token_value) }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Mode of Payments</td>
                        <td>{{ $mod_description->payment_description }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Location</td>
                        <td>{{ $location_name->location_name}}</td>
                        <td></td>
                    </tr>
                    @if ($mod_description->payment_description != "CASH")
                    <tr>
                        <td>Payment Reference</td>
                        <td>{{ $swap_histories->payment_reference}}</td>
                        <td></td>
                    </tr>
                    @endif
                    <tr>
                        <td>Created By</td>
                        <td>{{ $created_by->name}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Created Date</td>
                        <td>{{ $swap_histories->created_at}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Updated By</td>
                        <td>{{ $updated_by->name}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Updated Date</td>
                        <td>{{ $swap_histories->updated_at}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>{{ $swap_histories->status}}</td>
                        <td></td>
                    </tr>
                    @if (!empty($addons))
                        <tr>
                            <td colspan="3" style="background-color: #c02f2f; color: white;">Addons</td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td>Qty</td>
                            <td></td>   
                        </tr>
                        @foreach ($addons as $addon)
                            <tr>
                                <td>{{ $addon->description}}</td>
                                <td>{{ $addon->qty}}</td>
                                <td></td>
                            </tr>
                        @endforeach
                     @endif
                     @if (isset($defective_returns) && count($defective_returns) > 0)
                     <tr>
                         <td colspan="3" style="background-color: #c02f2f; color: white;">Jan Number Details</td>
                     </tr>
                     <tr>
                         <td>Jan Number</td>
                         <td>Action Type</td>
                         <td>Qty</td>
                     </tr>
                     @foreach ($defective_returns as $defective_return)
                         <tr>
                             <td>{{ $defective_return->digits_code}}</td>
                             <td>{{ $defective_return->description}}</td>
                             <td>{{ $defective_return->qty}}</td>
                         </tr>
                     @endforeach
                  @endif

                </tbody>
                <tfoot>
                    <tr>
                      <td colspan="3"><button class="btn-back" onclick="goBack()">Back</button></td>
                    </tr>
                  </tfoot>
            </table>
    </div>


@endsection


@section('script-js')
<script>
    
    $(document).ready(function() {
        $(".styled-table").fadeIn(1000);
    });
    function goBack() {
      window.history.back();
    }
    </script>

@endsection