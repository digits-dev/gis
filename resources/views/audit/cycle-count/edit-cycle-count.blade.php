@push('head')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/custom.css')}}">
    <style type="text/css">

        #edit-cycle-count th,  td {
            border: 1px solid rgba(000, 0, 0, .5);
        }

        @media (min-width:729px) {
            .panel-default {
                width: 40% !important;
                margin: auto !important;
            }
        }
        input.finput:read-only {
            background-color: #fff;
        }
    </style>
@endpush
@extends('crudbooster::admin_template')
@section('content')
    @if (g('return_url'))
        <p class="noprint"><a title='Return' href='{{ g('return_url') }}'><i class='fa fa-chevron-circle-left '></i> &nbsp;
                {{ trans('crudbooster.form_back_to_list', ['module' => CRUDBooster::getCurrentModule()->name]) }}</a></p>
    @else
        <p class="noprint"><a title='Main Module' href='{{ CRUDBooster::mainpath() }}'><i
                    class='fa fa-chevron-circle-left '></i> &nbsp;
                {{ trans('crudbooster.form_back_to_list', ['module' => CRUDBooster::getCurrentModule()->name]) }}</a></p>
    @endif

    <div class='panel panel-default'>
        <span><button class="btn btn-default btn-sm" id="btnUpload" style="float:right; margin: 5px 5px 0 0;"><i class="fa fa-upload"></i> Fill Qty via upload file</button></span>
        <div class='panel-heading' style="background-color:#3c8dbc; color:#fff">
            Edit Cycle Count Form
        </div>
        <form action="{{ route('submit-cycle-count-edit') }}" method="POST" id="editCycleCount" enctype="multipart/form-data">
            <input type="hidden" value="{{ csrf_token() }}" name="_token" id="token">
            <div class='panel-body'>
                <div class="col-md-12">
                    <input type="hidden" name="cycle_count_id" id="cycle_count_id" value="{{$detail_header->id}}">
                    <input type="hidden" name="cycle_count_type" id="cycle_count_type" value="{{$cycle_count_type}}">
                    <input type="hidden" name="locations_id" id="locations_id" value="{{$detail_header->locations_id}}">
                    
                    <div class="form-group">
                        <label class="control-label"> Reference Number</label>
                        <input type="text" class="form-control finput" value="{{ $detail_header->reference_number }}"
                            readonly>
                    </div>

                    <div class="form-group">
                        <label class="control-label"> Location</label>
                        <input type="text" class="form-control finput" value="{{ $detail_header->location_name }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="require control-label"> Sub Location</label>
                        <input type="text" class="form-control finput" value="{{ $detail_header->sub_location_name }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="require control-label"> Type</label>
                        <input type="text" class="form-control finput" value="{{ $cycle_count_type }}" readonly>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{CRUDBooster::adminpath("cycle_counts/exportedit/".$detail_header->id)}}" id="btn-export" class="btn btn-primary btn-sm btn-export" style="margin-bottom:10px"><i class="fa fa-download"></i>
                            <span>download template</span>
                        </a>
                        <table class="table" id="edit-cycle-count">
                            <thead>
                                <tr style="vertical-align: top;">
                                    <th width="20%" class="text-center">Machine</th>
                                    <th width="20%" class="text-center">Item Code</th>
                                    <th width="20%" class="text-center">Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detail_body as $row)
                                    <tr>
                                        <td style="text-align:center">
                                            {{ $row->serial_number }}
                                            <input type="hidden" name="machine[]"  value="{{$row->serial_number}}">
                                        </td>
                                        <td style="text-align:center">
                                            {{ $row->digits_code }}
                                            <input type="hidden" name="item_code[]"  value="{{$row->digits_code}}">
                                        </td>
                                        <td class="text-center">
                                            @if($cycle_count_type == "FLOOR")
                                                <input class="form-control finput qty" id="qty" style="text-align:center; border:none" name="qty[]" type="text" value="{{ $row->qty }}" item="{{ $row->digits_code."-".$row->serial_number }}" readonly>
                                            @else
                                                <input class="form-control finput qty" id="qty" style="text-align:center; border:none;" name="qty[]" type="text" value="{{ $row->qty }}" item="{{ $row->digits_code }}" readonly>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" style="text-align: center;"><b>Total</b></td>
                                    <td class="text-center"><input style="text-align: center; border:none" type="text" id="totalQty"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                @if ($detail_header->received_by != null)
                    <div class="row">
                        <div class="col-md-12">
                            <table style="width:100%">
                                <tbody id="footer">
                                    <tr>
                                        <th class="control-label col-md-2">{{ trans('message.form-label.received_by') }}:</th>
                                        <td class="col-md-4">{{ $detail_header->receiver_name }} /
                                            {{ $detail_header->received_at }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
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
                                    <input type="file" name="cycle-count-file" class="form-control" id="cycle-count-file">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="float: left;">Cancel</button>
                            <button type='button' id="upload-cycle-count" class="btn btn-primary"><i class="fa fa-upload"></i> Upload</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class='panel-footer'>
                <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.back') }}</a>
                <button class="btn btn-primary pull-right" type="submit" id="btnUpdate"> <i class="fa fa-refresh"></i>
                    {{ trans('message.form.update') }}</button>
            </div>
        </form>
    </div>
@endsection
@push('bottom')
<script src=
"https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js" >
</script>
<script src=
"https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" >
</script>
    <script src=
"https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js" >
</script>
    <script type="text/javascript">
        function preventBack() {
            window.history.forward();
        }
        window.onunload = function() {
            null;
        };
        setTimeout("preventBack()", 0);
        var table;
        $(document).ready(function() {
            // table = $("#edit-cycle-count").DataTable({
            //     bPaginate: false,
            //     bLengthChange: false,
            //     bFilter: false,
            //     bInfo: true,
            //     bAutoWidth: false,
            //     ordering:false,
            //     buttons: [
            //         {
            //             extend: "excel",
            //             title: "",
            //             exportOptions: {
            //                 columns: ":not(.not-export-column)",
            //                 columns: [1,2,3]
            //             },
            //         },
            //     ],
            // })
            // $("#btn-export").on("click", function () {
            //     table.button(".buttons-excel").trigger();
            // });
            //Show Modal upload file
            $("#btnUpload").click(function () {
                $('#addRowModal').modal('show');
            });
            //Upload file
            $('#upload-cycle-count').on('click', function(event) {
                event.preventDefault();
                if($('#cycle-count-file').val() === ''){
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
                    formData.append('cycle-count-file', $('#cycle-count-file')[0].files[0]);
                    formData.append('cycle_count_type', $('#cycle_count_type').val());
                    formData.append('locations_id', $('#locations_id').val());
                    formData.append('cycle_count_id', $('#cycle_count_id').val());
                    $.ajax({
                        type:'POST',
                        url: "{{ route('cycle-count-edit-file-store') }}",
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
                                        $('#addRowModal').modal('hide');
                                        overwriteTable(response.items);
                                        $('#totalQty').val(calculateTotalQuantityInput());
                                        $('#filename').val(response.filename);
                                        $('#btnUpload').attr('disabled', true);
                                    }
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
                        $('#editCycleCount').submit();
                    }
                });

            });

            $('#totalQty').val(calculateTotalQuantityInput());
            $('#totalQty').text(calculateTotalQuantity());
            $('#totalVariance').text(calculateVarianceQuantity());
        });

        function overwriteTable(data){
            data.forEach((item, index) => {
                console.log($('#cycle_count_type').val());
                const tr = $('#edit-cycle-count tbody').find('tr');
                let cycleCountType = '';
                if($('#cycle_count_type').val() === 'FLOOR'){
                     cycleCountType = item.item_code.concat('-', item.machine);
                }else{
                     item.machine = '';
                     cycleCountType = parseInt(item.item_code);
                }
                const qty = tr.find('input[item="'+cycleCountType+'"]');
                const newQty = parseInt(item.qty) ? parseInt(item.qty) : 0;
               
                if(qty.length > 0){
                    qty.val(newQty);
                }else{
                    const newrow =`
                        <tr class="item-row existing-machines" machine="${item.digits_code2}">
                            <td class="td-style text-center">${item.machine}
                                <input type="hidden" name="machine[]" value="${item.machine}">
                            </td>
                            <td class="td-style existing-item-code text-center">${item.item_code}
                                <input type="hidden" name="item_code[]"  value="${item.item_code}">
                            </td>
                            <td class="td-style">
                                <input machine="${item.machine}" item="${item.item_code.concat('-',item.machine)}" item_code="${item.item_code}" id="qty" class="text-center finput qty item-details" value=${item.qty} type="text" name="qty[]" style="width:100%; border:none" autocomplete="off" required readonly>
                            </td>
                        </tr>
                    `;
                    $('#edit-cycle-count tbody').append(newrow);
                }
            });
        }

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

        function calculateVarianceQuantity() {
            let varianceQuantity = 0;
            $('.variance').each(function() {
                let qty = 0;
                if ($(this).text().trim()) {

                    qty = parseInt($(this).text().replace(/,/g, ''));
                }

                varianceQuantity += qty;
            });
            return varianceQuantity;
        }

        function calculateTotalQuantityInput() {
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

        $('#qty').on('input',function(){
            validateQty();
        })

        function validateQty(){
            const inputs = $('.qty').get();
            let isValid = true;
            inputs.forEach(input =>{
                const currentVal = $(input).val(); 
                const value = Number(currentVal.replace(/\D/g, ''));

                if(!currentVal){
                    isValid = false;
                    $(input).css('border', '2px solid red');
                }else {
                    $(input).css('border', '');
                }
            });
            isValid = isValid;
            $('#btnUpdate').attr('disabled',!isValid);
        }

    </script>
@endpush
