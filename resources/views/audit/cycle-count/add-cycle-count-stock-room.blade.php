@push('head')
    <script src="{{ asset('plugins/sweetalert.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/code-scanner.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/custom.css')}}">
    <style type="text/css">
        .select2-selection__choice {
            font-size: 14px !important;
            color: black !important;
        }

        .select2-selection__rendered {
            line-height: 31px !important;
        }

        .select2-container .select2-selection--single {
            height: 35px !important;
        }

        .select2-selection__arrow {
            height: 34px !important;
        }

        .modal-content {
            -webkit-border-radius: 10px !important;
            -moz-border-radius: 10px !important;
            border-radius: 10px !important;
        }

        .modal-header {
            background-color: #3c8dbc;
            -webkit-border-radius: 10px !important;
            -moz-border-radius: 10px !important;
            border-radius: 10px 10px 0px 0px !important;
            color: #fff;
        }

        #other-detail th,
        td {
            border: 1px solid rgba(000, 0, 0, .5);
            padding: 8px;
        }

        #cycle-count th,
        td {
            border: 1px solid rgba(000, 0, 0, .5);
            padding: 8px;
        }

        .td-style{
            text-align: center;
            vertical-align: middle !important;
        }

        .plus {
            font-size: 20px;
        }

        .iconPlus {
            background-color: #3c8dbc:
        }

        .iconPlus:before {
            content: '';
            display: flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            /* border: 1px solid rgb(194, 193, 193); */
            font-size: 35px;
            color: white;
            background-color: #3c8dbc;

        }

        #bodytable tr td{
            text-align: center;
        }

        #bigplus {
            transition: transform 0.5s ease 0s;
        }

        #bigplus:before {
            content: '\FF0B';
            background-color: #3c8dbc: font-size: 50px;
        }

        #bigplus:hover {
            /* cursor: default;
                transform: rotate(180deg); */
            -webkit-animation: infinite-spinning 1s ease-out 0s infinite normal;
            animation: infinite-spinning 1s ease-out 0s infinite normal;

        }

        @keyframes infinite-spinning {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        @media (min-width:729px) {
            .panel-default {
                width: 70% !important;
                margin: auto !important;
            }
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
        <span><a class="btn btn-warning btn-sm" id="btnRefreshPage" style="float:right; margin: 5px 5px 0 0"><i class="fa fa-refresh"></i> Reset</a></span>
        <span><a class="btn btn-default btn-sm" id="btnExport" style="float:right; margin: 5px 5px 0 0"><i class="fa fa-download"></i> Download template</a></span>
        <span><button class="btn btn-default btn-sm" id="btnUpload" style="float:right; margin: 5px 5px 0 0;"><i class="fa fa-upload"></i> Upload file</button></span>
        <div class='panel-heading' style="background-color:#3c8dbc; color:#fff">
            <span>Cycle Count (Capsule) Form</span>
        </div>

        <form action="{{ route('submit-cycle-count-sr-file-upload') }}" method="POST" id="cycleCount" enctype="multipart/form-data">
            <input type="hidden" value="{{ csrf_token() }}" name="_token" id="token">
            <input type="hidden" name="filename" id="filename">
            <div class='panel-body'>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"><span style="color:red">*</span> Location</label>
                            <select selected data-placeholder="Choose location" id="location_id" name="location_id"
                                class="form-select select2" style="width:100%;">
                                @foreach ($locations as $location)
                                    <option value=""></option>
                                    <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                                @endforeach
                            </select>
                            <span class="label label-danger" id="location_error"></span>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-12">
                        <table class="table table-responsive" id="cycle-count">
                            <thead>
                                <tr>
                                    <th width="15%" class="text-center">Item Code</th>
                                    <th width="30%" class="text-center">Item Description</th>
                                    <th width="15%" class="text-center">Quantity</th>
                                    <th width="3%" class="text-center exclude"><i class="fa fa-trash"></i></th>
                                </tr>
                            </thead>
                            <tbody id="bodyTable">
                            </tbody>
                            <tfoot>
                                <tr id="tr-table1" class="bottom">
                                    <td colspan="2">
                                    </td>
                                    <td colspan="1">
                                        <input type="text" name="quantity_total" class="form-control text-center"
                                            id="quantity_total" readonly>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Modal upload file --}}
            <div id="addRowModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">

                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title text-center"><strong>Cycle Count (Upload File)</strong></h4>
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
                <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default" id="btn-cancel">{{ trans('message.form.cancel') }}</a>
                <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save"></i>
                    {{ trans('message.form.new') }}</button>
            </div>
        </form>
    </div>
@endsection
@push('bottom')
    <script type="text/javascript">
        // function preventBack() {
        //     window.history.forward();
        // }
        // window.onunload = function() {
        //     null;
        // };
        // setTimeout("preventBack()", 0);
        $('#addRowModal').modal('hide');
        $('#btnExport').attr('disabled', true);
        $('#btnUpload').attr('disabled', true);
        $('#location_id').select2();

        var tableRow = 1;
        var machineArray = [];

        let selectedCameraId = null;
        let selectedInput = null;
        let html5QrCode = null;
        let timeout;
        let machineTokenNo = 0;
        let itemTokenNo = 0;

        $('#location_id').change(function() {
            const url_download = '{{CRUDBooster::adminpath("cycle_counts/download/")}}';
            $('#btnExport').attr('href', url_download+'/'+$(this).val());
            $(this).attr('disabled', true);
            $('#btnExport').attr('disabled', false);
            $('#btnUpload').attr('disabled', false);
            $.ajax({
                type: 'POST',
                url: "{{ route('check-sr-inventory-qty') }}",
                data: {
                    "location_id": $(this).val()
                },
                success: function(res) {
                    const data = JSON.parse(res);
                    if ($.isEmptyObject(data.capsuleInventory)) {
                        $('#location_error').text('No available inventory');
                    } else {
                        $('#location_error').text('');
                        populateOutsideTable(data.capsuleInventory);
                        $('#quantity_total').val(calculateTotalQuantity());
                    }
                    // console.log(res);
                }
            });
        });

        $(document).on('keyup', '.qty', function(e) {
            if (event.which >= 37 && event.which <= 40) return;

            if (this.value.charAt(0) == '.') {
                this.value = this.value.replace(/\.(.*?)(\.+)/, function(match, g1, g2) {
                    return '.' + g1;
                })
            }

            if (this.value.split('.').length > 2) {
                this.value = this.value.replace(/([\d,]+)([\.]+.+)/, '$1') +
                    '.' + this.value.replace(/([\d,]+)([\.]+.+)/, '$2').replace(/\./g, '')
                return;
            }

            $(this).val(function(index, value) {
                value = value.replace(/[^0-9.]+/g, '')
                let parts = value.toString().split(".");
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                return parts.join(".");
            });

            $(this).val(function(index, value) {
                return value
                    .replace(/\D/g, "")
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            });

            $('#quantity_total').val(calculateTotalQuantity());
        });
        const fileLength = [];
        $(document).ready(function() {
            $('#btnSubmit').click(function(event) {
                event.preventDefault();
                var rowCount = $('#cycle-count tr').length - 2;
                // console.log(rowCount);
                if (rowCount === 0) {
                    swal({
                        type: 'error',
                        title: 'Please add an item!',
                        icon: 'error',
                        confirmButtonColor: "#3c8dbc",
                    });
                    event.preventDefault();
                    return false;
                } else if ($('#location_id').val() === '') {
                    swal({
                        type: 'error',
                        title: 'Location cannot be empty!',
                        icon: 'error',
                        confirmButtonColor: '#3c8dbc',
                    });
                    event.preventDefault();
                    return false;
                }


                // let qty = $('input[name^="qty[]"]').length;
                // let qty_value = $('input[name^="qty[]"]');
                // for (i = 0; i < qty; i++) {
                //     if (qty_value.eq(i).val() == '' || qty_value.eq(i).val() == null) {
                //         swal({
                //             type: 'error',
                //             title: 'Qty cannot be empty!',
                //             icon: 'error',
                //             confirmButtonColor: '#3c8dbc',
                //         });
                //         event.preventDefault();
                //         return false;
                //     }
                // }

                Swal.fire({
                    title: 'Are you sure ?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Save',
                    returnFocus: false,
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#location_id').attr('disabled', false);
                        $('#btnSubmit').attr('disabled',true);
                        $('.qty').attr('readonly',true);
                        $('#cycleCount').submit();
                    }
                });

            });
        });

        // fix for bug when pressing enter key removing lines on outside table
        $(document).on('keypress', 'input', function(event) {
            if (event.key == 'Enter') {
                event.preventDefault();
            }
        });

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

        $(document).on('click', '#deleteRow', function() {
            const machine = $(this).attr('machine');
            $(`tr[machine="${machine}"]`).remove();
            $('#quantity_total').val(calculateTotalQuantity());
        });

        function populateOutsideTable(data){

            data.forEach((item, index) => {
                const newrow =`
                    <tr class="item-row existing-machines" style="background-color: #d4edda; color:#155724" machine="${item.digits_code2}">

                        <td class="td-style">${item.digits_code}
                            <input type="hidden" name="item_code[]" value="${item.digits_code}">
                        </td>

                        <td class="td-style existing-item-description">${item.item_description}</td>

                        <td class="td-style">
                            <input machine="${item.digits_code2}" item="${item.digits_code}" description="${item.item_description}" class="form-control text-center finput qty item-details" type="text" name="qty[]" style="width:100%" autocomplete="off" required readonly>
                        </td>

                        <td class="td-style exclude">
                            ${index+1}
                        </td>

                    </tr>

                `;

                $('#cycle-count tbody').append(newrow);
            });
        }

        $(document).on('submit', 'form#cycle-count', function(vent) {
            const itemsToBeSubmitted = [];
            const items = $(this).find('.item-details').get();
            items.forEach(item => {
                item = $(item);
                itemsToBeSubmitted.push({
                    item_code: item.attr('item_code'),
                    machine: item.attr('machine'),
                    description: item.attr('description'),
                    qty: item.val(),
                });
            });
        });

        //Show Modal upload file
        $("#btnUpload").click(function () {
            $('#addRowModal').modal('show');
        });

        $('#addRowModal').on('hidden.bs.modal', function (e) {
            $(this).find("input,textarea").val('').end();
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
                $.ajax({
                    type:'POST',
                    url: "{{ route('cycle-count-file-store') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: (response) => {
                        console.log(response.files);
                        if (response) {
                            Swal.fire({
                                title: response.msg,
                                icon: response.status,
                                confirmButtonColor: '#3085d6',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('#addRowModal').modal('hide');
                                    response.files.forEach(file=>{
                                        const tr = $('#cycle-count tbody').find('tr');
                                        const qty = tr.find('input[item="'+parseInt(file.digits_code)+'"]');
                                        const newQty = parseInt(file.quantity) ? parseInt(file.quantity) : 0;
                                        if(qty){
                                            qty.val(newQty);
                                        }
                                        $('#quantity_total').val(calculateTotalQuantity());
                                    });
                                    fileLength.push(response.files);
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

        $("#btn-cancel").click(function(event) {
            event.preventDefault();
            Swal.fire({
                    title: 'Are you sure you want to cancel?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: "No",
                    returnFocus: false,
                    reverseButtons: true,
            }).then((result) => {
                    if (result.isConfirmed) {
                        window.history.back();    
                    }
            });
        });

        //PAGE RESET DELETE FILE
        $("#btnRefreshPage").click(function(event) {
            event.preventDefault();
            const filename = $('#filename').val();
            if(!filename){
                swal({
                    type: 'error',
                    title: 'Nothing to reset!',
                    icon: 'error',
                    confirmButtonColor: "#3c8dbc",
                });
                event.preventDefault();
                return false;
            }else{
                Swal.fire({
                        title: 'Are you sure you want to reset?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        cancelButtonText: "No",
                        returnFocus: false,
                        reverseButtons: true,
                }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajaxSetup({
                                headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        }
                            });
                            $.ajax({
                                type: 'POST',
                                url: '{{ route("delete-file") }}',
                                dataType: 'json',
                                data: {
                                    'filename': filename
                                },
                                success: (response) => {
                                    Swal.fire({
                                        title: response.message,
                                        icon: response.status,
                                        confirmButtonColor: '#3085d6',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            location.reload();
                                        }
                                    });   
                                    
                                }
                            });  
                        }
                });
            }
        });
    </script>
@endpush
