@extends('crudbooster::admin_template')
@section('content')
<style>
.content{
    width: 600px;
}
.swal2-popup, .swal2-modal, .swal2-icon-warning .swal2-show {
    font-size: 1.6rem !important;
}
.style-table-void {
    width: 100%;
}
.style-table-void th{
 padding: 10px;
 border:1px solid black;
 background-color: #dd4b39;
 color: white;
}
.style-table-void td{
    border:1px solid black;
    padding: 10px;
    text-align: center;
}
</style>
<div class='panel panel-default'>
    <div class='panel-heading' style="background-color:#dd4b39; color:#fff">
        VOID
    </div>
    
    <form action="{{ CRUDBooster::mainpath('edit-save/'. $swap_histories->id) }}" method="POST" id="void" enctype="multipart/form-data">
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
       
        <div class='panel-body'>
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
                    <label class="require control-label">Status:</label>
                    <input type="text" class="form-control" value="{{ $swap_histories->status}}" disabled>
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
            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
            <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> VOID</button>
        </div>
    </form>
    </div>
@endsection
@push('bottom')
<script src="{{ asset('plugins/sweetalert.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script type="text/javascript">

    function preventBack() {
        window.history.forward();
    }
     window.onunload = function() {
        null;
    };
    setTimeout("preventBack()", 0);
    $(document).ready(function() {
        $('#btnSubmit').click(function(event) {
            event.preventDefault();
            Swal.fire({
                    title: 'Are you sure you want to void this transaction?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Save',
                    returnFocus: false,
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#void').submit();
                    }
                });
        });
    });

    
</script>
@endpush