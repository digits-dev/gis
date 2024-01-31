@push('head')
<script src="{{ asset('plugins/sweetalert.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="{{ asset('css/custom.css')}}">
    <style type="text/css">   
        #other-detail th, td {
            border: 1px solid rgba(000, 0, 0, .5);
            padding: 8px;
        }
        #collected-token th, td {
            border: 1px solid rgba(000, 0, 0, .5);
            padding: 8px;
        }

        /* @media (min-width:729px){
           .panel-default{
                width:40% !important; 
                margin:auto !important;
           }
        } */

        input.finput:read-only {
            background-color: #fff;
            border: none;
        }

    </style>
@endpush
@extends('crudbooster::admin_template')
@section('content')
@if(g('return_url'))
<p class="noprint"><a title='Return' href='{{g("return_url")}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@else
<p class="noprint"><a title='Main Module' href='{{CRUDBooster::mainpath()}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@endif

<div class='panel panel-default'>
<div class='panel-heading' style="background-color:#3c8dbc; color:#fff">
    Collected Token For Approval
</div>

    <div class='panel-body'>
        <div class="row">
            <div class="col-md-12">
                <form action="{{ CRUDBooster::mainpath('edit-save/'.$location_id) }}" method="POST" id="collectTokenForApproval" enctype="multipart/form-data">
                    <input type="hidden" name="location_id" value="{{$location_id}}">
                    <table class="table" id="collected-token">
                        <tbody id="bodyTable">    
                                <tr>
                                    <th width="10%" class="text-center">Reference #</th> 
                                    <th width="10%" class="text-center">Machine</th> 
                                    <th width="10%" class="text-center">Machine no of tokens</th> 
                                    <th width="10%" class="text-center">Collected tokens</th>
                
                                </tr>      
                            @foreach($items as $row)
                                <tr>
                                    <td style="text-align:center" height="10">
                                        {{$row->reference_number}}                               
                                    </td>
                                    <td style="text-align:center" height="10">
                                        {{$row->serial_number}}                               
                                    </td>
                                    <td style="text-align:center" height="10">
                                        {{$row->no_of_token_line}}                              
                                    </td>
                                    <td qty="{{$row->qty}}" no-of-token="{{$row->no_of_token_line}}" style="text-align:center" height="10" class="qty">
                                        {{$row->qty}}                               
                                    </td>
                                </tr>
                            @endforeach                                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" style="text-align: center;"><b>Total</b></td>
                                <td class="text-center"><span id="totalQty">0</span></td>
                            </tr>
                        </tfoot>
                    </table>
                </form>
            </div>   
        </div>
    </div>
    <div class='panel-footer'>
        <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.back') }}</a>
        <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-thumbs-up"></i> {{ trans('message.form.approved') }}</button>
    </div>

</div>

@endsection
@push('bottom')

<script type="text/javascript">

    function preventBack() {
        window.history.forward();
    }
     window.onunload = function() {
        null;
    };
    setTimeout("preventBack()", 0);
    $('#location_id').select2();

    $(document).ready(function() {
        $('#totalQty').text(calculateTotalQuantity());
        isDivisible();
    });

    $('#btnSubmit').click(function(event) {
        Swal.fire({
            title: 'Are you sure ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Save',
            returnFocus: false,
            reverseButtons: true,
            showLoaderOnConfirm: true,
        }).then((result) => {
            if (result.isConfirmed) {
                
                const formData = $('form').serialize();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('submit-collect-token-approval') }}",
                    data: formData,
                    success: function(res) {
                        const response = JSON.parse(res);
                        if(response.status == 'success'){
                            Swal.fire({
                                type: response.status,
                                title: response.msg,
                                icon: response.status,
                                confirmButtonColor: "#3c8dbc",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.replace(response.redirect_url);
                                }
                            });; 
                        }
                        $('#save-btn').attr('disabled', false);
                    },
                    error: function(err) {
                        Swal.fire({
                            title: "Oops.",
                            html:  'Something went wrong!',
                            icon: 'error',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok',
                            returnFocus: false,
                            reverseButtons: true,
                        });
                    }
                });
                Swal.fire({
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    title: "Please wait while saving...",
                    didOpen: () => Swal.showLoading()
                });
            }
        });
    });

    function calculateTotalQuantity() {
        let totalQuantity = 0;
        $('.qty').each(function() {
            let qty = 0;
            if($(this).text().trim()) {
                qty = parseInt($(this).text().replace(/,/g, ''));
            }

            totalQuantity += qty;
        });
        return totalQuantity;
    }

    function isDivisible() {
        const inputs = $('.qty').get();
        console.log(inputs);
        inputs.forEach(input => {
            const qty = Number($(input).attr('qty')); 
            const noOfToken = Number($(input).attr('no-of-token'));
            
            if (qty % noOfToken === 0) {
                $(input).css('border', '');
            } else {
                $(input).css('border', '2px solid red');
            }
        });
    }
</script>
@endpush