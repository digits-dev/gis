@extends('crudbooster::admin_template')
@section('content')
<style>
    .content{
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    }

.swal2-popup, .swal2-modal, .swal2-icon-warning .swal2-show {
    font-size: 1.6rem !important;
}
.panel-body-container{
    display: flex;
    align-items: center;
}
.style-table-void {
    width: 100%;
    height: 100%;
}
.style-table-void th{
 padding: 10px;
 border:1px solid black;
 background-color: #eeeeee;
}
.style-table-void td{
    border:1px solid black;
    padding: 10px;
    text-align: center;
    background-color: #eeeeee;
}
</style>
<div class='panel panel-default' style="width: <?php echo (!$addons && !$defective_returns) ? '50%' : '100%'; ?>">
    <div class='panel-heading'>
        Detail Token Swap History
    </div>
        <div class='panel-body'>
            <div class="panel-body-container">
                <div class="col-md-12">
            
                    <div class="form-group">
                        <label class="require control-label">Reference Number:</label>
                        <input type="text" class="form-control" value="{{ $swap_histories->reference_number }}" disabled>
                    </div>
                    <div class="form-group">
                        <label class="require control-label">Value:</label>
                        <input type="text" class="form-control" value="{{ $swap_histories->total_value }}" disabled>
    
                    </div>
                    <div class="form-group">
                        <label class="require control-label">Token:</label>
                        <input type="text" class="form-control" value="{{ $swap_histories->token_value}}" disabled>
                    </div>
                    <div class="form-group">
                        <label class="require control-label">Payment Reference:</label>
                        <input type="text" class="form-control" value="{{ $swap_histories->payment_reference}}" disabled>
                    </div>
                    <div class="form-group">
                        <label class="require control-label">Mode of Payment:</label>
                        <input type="text" class="form-control" value="{{ $mod_description->payment_description }}" disabled>
                    </div>
                    <div class="form-group">
                        <label class="require control-label">Location:</label>
                        <input type="text" class="form-control" value="{{ $location_name->location_name }}" disabled>
                    </div>
                    <div class="form-group">
                        <label class="require control-label">Status:</label>
                        <input type="text" class="form-control" value="{{ $swap_histories->status}}" disabled>
                    </div>
            </div>
          
                @if (!empty($addons))
                    <table class="style-table-void">
                        <thead>
                            <tr>
                                <th colspan="2" style="text-align: center;">Addons</th>
                            </tr>
                        </thead>
                        <tbody>
                        
                            <tr>
                                <td style="font-weight: bold;">Description</td>
                                <td style="font-weight: bold;">Qty</td>
                            </tr>
                            @foreach ($addons as $addon)
                                <tr>
                                    <td>{{ $addon->description}}</td>
                                    <td>{{ $addon->qty}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif

                    @if (isset($defective_returns) && count($defective_returns) > 0)
                    <table class="style-table-void">
                        <thead>
                            <tr>
                                <th colspan="3" style="text-align: center;">Jan Number Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-weight: bold;">Digits Code</td>
                                <td style="font-weight: bold;">Description</td>
                                <td style="font-weight: bold;">Qty</td>
                            </tr>
                            @foreach ($defective_returns as $defective_return)
                                <tr>
                                    <td>{{ $defective_return->digits_code}}</td>
                                    <td>{{ $defective_return->item_description}}</td>
                                    <td>{{ $defective_return->qty}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
            </div>
        </div>
        <div class='panel-footer'>
            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">BACK</a>
        </div>
    </div>
@endsection
@push('bottom')
<script src="{{ asset('plugins/sweetalert.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script type="text/javascript">

    
</script>
@endpush