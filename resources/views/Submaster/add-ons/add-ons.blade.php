@extends('crudbooster::admin_template')
@push('head')
    <link rel="stylesheet" href="{{ asset('css/add-ons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script src="{{ asset('plugins/sweetalert.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/select2-custom.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
    input:read-only {
        background-color: #dddd;
    }

    .search-icon {
        padding-top: 2px;
    }
    </style>
@endpush
@section('content')


@if (session('success'))
<div class="custom-swal-div">
    <span class="material-symbols-outlined notif-icon">notifications</span>
    {{-- <span class="material-symbols-outlined notif-icon">check</span> --}}
    <div class="spanContainer">
        <span class="custom-swal">Wow, Good Job...</span>
        <span class="custom-swal swal-message" >{{ session('success') }}</span>
    </div>
</div>
@endif

<div class="panel-content">
    <div class='panel panel-default'>
        <form action="{{ route('submitAddOns') }}" method="POST" autocomplete="off" id="addOnForm">
            @csrf
            <div class='panel-header'>
                <label>Add</label>
            </div>
            <div class='panel-body'>
                <div class="display_qty_div">
                    <label class="control-label"><span style="color:red">*</span>Choose location</label>
                    <select selected data-placeholder="Choose location" validation-name="Location" id="location" name="location" class="form-select select2" style="width:100%;" required>
                    @foreach($locations as $location)
                    <option value=""></option>
                        <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="display_qty_div">
                    <label><span style="color:red">*</span>DR Number:</label>
                    <input type="text" class="form-control" name ="dr_number" id ="dr_number" required>
                </div>
                <div class="display_qty_div ">
                    <label><span style="color:red">*</span>Digits Code:</label>
                    <div class="search_div">
                    <input type="text" class="form-control" name ="int_digits_code" id ="int_digits_code" oninput="numberOnly(this)" required>
                    <button class="btn btn-primary search-btn" id="check-btn" type="button" ><span class="material-symbols-outlined search-icon">search</span></button>
                    </div>
                </div>
                <div class="display_qty_div">
                    <label>Item Description:</label>
                    <input type="text"  class="form-control" name="int_description" id="int_description" readonly>
                </div>
                <div class="display_qty_div">
                    <label><span style="color:red">*</span>Qty:</label>
                    <input type="text" class="form-control" name ="int_qty" id ="int_qty" oninput="formatNumber(this)" required>
                </div>
            </div>
            <div class='panel-footer' >
                <button type="submit" id="real-submit-btn" style="display: none;">Real submit</button>
                <button class="btn btn-default" id="cancel-btn" type="button" >Back</button>
                <button class="btn btn-default" id="real-cancel-btn" type="button" style="display: none;"> Real Back</button>
                <button class="btn btn-primary" id="save-btn" type="button" disabled><span class="material-symbols-outlined save-icon">save</span>Save</button>
            </div>
        </form>
    </div>
</div>

@endsection
@push('bottom')
<script>
       $('#location').select2();

    $(document).ready(function(){
        $('#check-btn').on('click',function(){
            const digitsCode = $('#int_digits_code').val();
            if (!digitsCode){
                clearInput();
                return;
            }
            $.ajax({
                    url:"{{ route('getDescription') }}",
                    type:"POST",
                    dataType:'json',
                    data:{
                        _token:"{{ csrf_token() }}",
                        digits_code: digitsCode,
                    },
                    success:function(res){
                        console.log(res);
                        populateDescription(res);
                        if (!res.description ){
                            $('#save-btn').attr('disabled',true);
                        } else {
                            $('#save-btn').attr('disabled',false);
                            $('#int_digits_code').attr('readonly',true);
                        };
                    },
                    error:function(err){
                        console.log(err)
                    },
                });
        });
        $('#real-cancel-btn').on('click', function(){
            window.location.href = '{{ CRUDBooster::mainPath() }}';
        });

        $('#int_digits_code').on('keydown', function(event){
            if(event.key === 'Enter'){
                $('#check-btn').click();
            };
        })

        $('#int_qty').on('keydown', function(event){
            if(event.key === 'Enter' && !($('#save-btn').attr('disabled'))){
                $('#save-btn').click();
            };
        });
    });

    function populateDescription(res){
        // console.log(res.description);
        const itemDescription = res.description;
        if (!itemDescription){
            $('#int_description').val('Item Code Does Not Exist');
        } else {
            $('#int_description').val(res.description);
            $('#int_qty').focus();
        };

    };
    function formatNumber(input) {
        input.value = input.value.replace(/\D/g, '').replace(/^0+/, '');
        input.value = input.value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    };

    function numberOnly(input) {
        input.value = input.value.replace(/\D/g, '');
    };

    function clearInput(){
        const noDigitCode = $('#int_digits_code').val();
        if (!noDigitCode){
            $('#int_description').val('');
        }
    }

    $('#cancel-btn').on('click', function(){
        Swal.fire({
            title: "Are Sure You Want To Go Back?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: "No",   
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            returnFocus: false,
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
            $('#real-cancel-btn').click();
            }
        });
    });

    setTimeout(function() {
        $('.custom-swal-div').fadeOut();
    }, 3000);


    $('#save-btn').on('click', function(){
        const digitsCodeValue = $('#int_digits_code').val();
        const qtyValue = $('#int_qty').val();
        const descriptionValue = $('#int_description').val();
        const statusValue = $('#int_status').val();

        Swal.fire({
            title: "Are Sure You Want To Submit?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Submit',
            returnFocus: false,
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                const form = $('#addOnForm');
                form.find('[name="int_digits_code"]').val(digitsCodeValue);
                form.find('[name="int_qty"]').val(qtyValue);
                form.find('[name="int_description"]').val(descriptionValue);
                form.find('[name="int_status"]').val(statusValue);

                if (!(event.keyCode === 13 || event.which === 13)) {
                    $('#real-submit-btn').click();
                }
            }
        });


    });

    $('#addOnForm').on('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });




</script>
@endpush
