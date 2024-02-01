@push('head')
<script src="{{ asset('plugins/sweetalert.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style type="text/css">   
        #other-detail th, td {
            border: 1px solid rgba(000, 0, 0, .5);
            padding: 8px;
        }
        #collected-token th, td {
            border: 1px solid rgba(000, 0, 0, .5);
            padding: 8px;
        }
        @media (min-width:729px){
           .panel-default{
                width:40% !important; 
                margin:auto !important;
           }
        }
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
    <span><button class="btn btn-default btn-sm" id="btnUpload" style="float:right; margin: 5px 5px 0 0;"><i class="fa fa-upload"></i> Fill Qty via upload file</button></span>
    <div class='panel-heading' style="background-color:#3c8dbc; color:#fff">
        Received collected token form
    </div>

    <form action="{{ route('submit-collect-token-edit') }}" method="POST" id="updateCollectToken" enctype="multipart/form-data">
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <input type="hidden" value="{{ $detail_header->ct_id }}" name="ct_id" id="ct_id">
        <input type="hidden" value="{{ $detail_header->collected_qty }}" name="collected_qty" id="collected_qty">
        <input type="hidden" value="{{ $detail_header->location_id }}" name="location_id" id="location_id">
        <div class='panel-body'>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="require control-label"> Reference #</label>
                            <input type="text" class="form-control" style="" value="{{ $detail_header->reference_number }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="require control-label"> Location</label>
                            <input type="text" class="form-control" style="" value="{{ $detail_header->location_name }}" readonly>
                        </div>
                    </div>
                </div>
                <a href="{{CRUDBooster::adminpath("collect_rr_tokens/exportedit/".$detail_header->ct_id)}}" id="btn-export" class="btn btn-primary btn-sm btn-export" style="margin-bottom:10px"><i class="fa fa-download"></i>
                    <span>download template</span>
                </a>
                <table class="table" id="collected-token">
                    <thead>
                        <tr>
                            <th width="20%" class="text-center">Machine</th> 
                            <th width="20%" class="text-center">Machine no of tokens</th> 
                            <th width="5%" class="text-center">Collected tokens</th>
                        </tr> 
                    </thead>
                                
                    <tbody id="bodyTable">
                        @foreach($detail_body as $row)
                            <tr>
                                <td style="text-align:center" height="10">
                                    {{$row->serial_number}}     
                                    <input type="hidden" name="machine[]" value="{{$row->serial_number}}">                          
                                </td>
                                <td style="text-align:center" height="10">
                                    {{$row->no_of_token_line}}    
                                    <input type="hidden" name="no_of_token[]"  value="{{$row->no_of_token_line}} ">                          
                                </td>
                                <td qty="{{$row->qty}}" no-of-token="{{$row->no_of_token_line}}" style="text-align:center" height="10" class="tdQty">
                                    <input type="text" value="{{$row->qty}}" class="text-center finput qty" name="qty[]" id="qty" item="{{$row->serial_number.'-'.$row->no_of_token_line}}" readonly>                             
                                </td>
                            </tr>
                        @endforeach                                            
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" style="text-align: center;"><b>Total</b></td>
                            <td class="text-center"><input id="totalQty" type="text" class="finput text-center" name="newTotalQty" readonly></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Modal upload file --}}
        <div id="addRowModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">

                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center"><strong>Fill Qty via upload file</strong></h4>
                    </div>
                    <div class="row">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="form-group" >
                                <input type="file" name="collect-token-file" class="form-control" id="collect-token-file">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="float: left;">Cancel</button>
                        <button type='button' id="upload-collect-token" class="btn btn-primary"><i class="fa fa-upload"></i> Upload</button>
                    </div>
                </div>
            </div>
        </div>

        <div class='panel-footer'>
            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
            <button class="btn btn-primary pull-right" type="submit" id="btnUpdate"> <i class="fa fa-refresh" ></i> {{ trans('message.form.update') }}</button>
        </div>
    </form>

    
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
    $('#location').select2();
    $(document).ready(function() {
        $('#btnUpdate').attr('disabled',true);
        $('#totalQty').val(calculateTotalQuantity());
        isDivisible();
        $("#btnUpload").click(function () {
            $('#addRowModal').modal('show');
        });

        //Upload file
        $('#upload-collect-token').on('click', function(event) {
            event.preventDefault();
            if($('#collect-token-file').val() === ''){
                swal({
                    type: 'error',
                    title: 'Please choose file!',
                    icon: 'error',
                    confirmButtonColor: '#3c8dbc',
                });
                event.preventDefault();
                return false;
            }else{
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            
                let formData = new FormData();
                formData.append('collect-token-file', $('#collect-token-file')[0].files[0]);
                formData.append('location_id', $('#location_id').val());
                $.ajax({
                    type:'POST',
                    url: "{{ route('collect-token-edit-file-store') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: (response) => {
                        console.log(response.items);
                        if (response) {
                            Swal.fire({
                                title: response.msg,
                                icon: response.status,
                                confirmButtonColor: '#3085d6',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('#btnUpdate').attr('disabled',false);
                                    $('#addRowModal').modal('hide');
                                    overwriteTable(response.items);
                                    $('#totalQty').val(calculateTotalQuantity());
                                    $('#filename').val(response.filename);
                                    $('#btnUpload').attr('disabled', true);
                                   
                                }
                                isDivisible();
                            });  
                        }
                    },
                    error: function(response){
                        $('#file-input-error').text(response.responseJSON.message);
                    }
                });
            }
        });

        //SUBMIT FORM   
        $('#btnUpdate').click(function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to update?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                returnFocus: false,
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#btnUpdate').attr('disabled',true);
                    $('#updateCollectToken').submit();
                }
            });

        });

    });

    function overwriteTable(data){
        $('#collected-token tbody').empty();
        data.forEach((item, index) => {
            const tr = $('#collected-token tbody').find('tr');
            collectTokenConcat = item.machine.concat('-', item.no_of_token);
        
            const qty = tr.find('input[item="'+collectTokenConcat+'"]');
            const newQty = parseInt(item.qty) ? parseInt(item.qty) : 0;
            
            // if(qty.length > 0){
            //     qty.val(newQty);
            // }else{
                const newrow =`
                    <tr class="item-row existing-machines" machine="${item.machine}">
                        <td class="td-style text-center">${item.machine}
                            <input type="hidden" name="machine[]" value="${item.machine}">
                        </td>
                        <td class="td-style existing-item-code text-center">${item.no_of_token}
                            <input type="hidden" name="no_of_token[]"  value="${item.no_of_token}">
                        </td>
                        <td class="tdQty" qty="${item.qty}" no-of-token="${item.no_of_token}">
                            <input item="${item.machine.concat('-',item.no_of_token)}" id="qty" class="text-center finput qty" value=${item.qty} type="text" name="qty[]" style="width:100%; border:none" autocomplete="off" required readonly>
                        </td>
                    </tr>
                `;
                $('#collected-token tbody').append(newrow);
            //}
        });
    }

    function calculateTotalQuantity() {
        let totalQuantity = 0;
        $('.qty').each(function() {
            let qty = 0;
            if (!($(this).val() === '')) {
                qty = parseInt($(this).val().replace(/,/g, ''));
            }

            totalQuantity += qty;
        });
        return totalQuantity;
    }

    function isDivisible() {
        const inputs = $('.tdQty').get();
     
        inputs.forEach(input => {
            const qty = Number($(input).attr('qty')); 
            const noOfToken = Number($(input).attr('no-of-token'));
            console.log(qty, noOfToken);
            if (qty % noOfToken === 0) {
                $(input).css('border', '');
            } else {
                $(input).css('border', '2px solid red');
            }
        });
    }
</script>
@endpush