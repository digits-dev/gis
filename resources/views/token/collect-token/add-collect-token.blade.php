@extends('crudbooster::admin_template')
@push('head')
<script src="{{ asset('plugins/sweetalert.js') }}"></script>
<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    .form-content {
        display: flex;
        background: #fff;
        flex-direction: column;
        font-family: 'Poppins', sans-serif !important;
        border-radius: 10px;
    }

    .header-title {
        background: #3C8DBC !important;
        color: #fff !important;
        font-size: 16px;
        font-weight: 500;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .content-panel {
        padding: 15px;
    }

    .inputs-container{
        display: flex;
        gap: 10px;
    }

    .input-container{
        flex: 1;
    }

    .form-button .btn-submit {
        padding: 9px 15px;
        background: #3C8DBC;
        border: 1.5px solid #1d699c;
        border-radius: 5px;
        color: white;
        user-select: none;
    }

    .form-button .btn-submit:hover {
        opacity: 0.7;
    }

    /* CUSTOM SELECT */

    .custom-select {
        position: relative;
        width: 100%;
    }

    select {
        width: 100%;
        padding: 9px;
        font-size: 14px;
        border: 1px solid #3C8DBC;
        border-radius: 4px;
        appearance: none;
        background-color: #fff;
        outline: none;
    }

    .custom-select::after {
        content: '▼';
        font-size: 12px;
        color: #3C8DBC;
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        transition: transform 0.3s ease;
    }

    .custom-select.open::after {
        transform: translateY(-50%) rotate(180deg);
    }

    select::-ms-expand {
        display: none;
    }

    option[disabled] {
        color: #bbb;
    }


    /* TABLE */

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 12px;
        
    }

    th, td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: center;
    }

    th {
        background-color: #3C8DBC;
        color: white;
        font-weight: bold;
        
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    input[type="text"] {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
        border: 1px solid #3C8DBC; 
        outline-color: #3C8DBC
    }

    input[type="text"]:disabled {
        background-color: #F3F3F3;
    }

  

    /* Spinner overlay container */
    .spinner-overlay {
        position: relative;
        display: inline-block;
        width: 100%;
        height: 100%;
    }

    /* The spinner itself */
    .spinner {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 30px;
        height: 30px;
        border: 4px solid whitesmoke;
        border-top: 4px solid #007bff;  /* Spinner color */
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    /* Animation for the spinner */
    @keyframes spin {
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        100% { transform: translate(-50%, -50%) rotate(360deg); }
    }

    /* Show spinner when active */
    .spinner.show {
        display: block;
    }

    .swal2-popup {
        width: 500px !important; 
        height: 80% !important;
    }
    .swal2-title {
        font-size: 24px !important; 
    }
    .swal2-html-container {
        font-size: 16px !important;
        overflow: hidden !important;
    }

    .swal2-confirm {
        font-size: 16px !important;
        padding: 10px 20px !important;
        border-radius: 5px !important;
        color: white !important;
    }
    .swal2-cancel {
        font-size: 16px !important;
        padding: 10px 20px !important;
        border-radius: 5px !important;
        color: white !important;
    }

    .swal2-icon {
        font-size: 16px !important; 
        width: 80px !important;
        height: 80px !important;
    }

    /* The backdrop (gray transparent background) */
    .loading-backdrop {
        position: fixed;    
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5); /* Dark semi-transparent background */
        display: none;      /* Initially hidden */
        justify-content: center;  /* Horizontally center the card */
        align-items: center;     /* Vertically center the card */
        z-index: 9999;      /* Ensure it's on top of other content */
        display: flex;      /* Flexbox for centering */
    }

    /* Loading card styles */
    .loading-card {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);  /* Slight shadow for card effect */
        text-align: center;
        width: 200px; /* Small fixed width for the card */
    }

    /* Spinner styles */
    .spinner {
        border: 5px solid lightgrey; /* Light border */
        border-top: 5px solid #3498db; /* Blue color for the spinner */
        border-radius: 50%;
        width: 55px;
        height: 55px;
        margin: 0 auto 10px;  /* Centered with margin below */
        animation: spin2 0.7s linear infinite;  /* Rotation animation */
    }

    /* Animation for the spinner */
    @keyframes spin2 {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

</style>
@endpush
@section('content')
<div class="panel panel-default form-content" style="overflow: hidden">
    <form id="collect_token" method="POST" action="{{route('postCollectedToken')}}">
        @csrf
        <input type="hidden" name="header_location_id" id="header_location_id" readonly>
        <input type="hidden" name="header_bay_id" id="header_bay_id" readonly>
        <div class="panel-heading header-title text-center">Add Collect Token Form</div>
        <div class="content-panel">
            <div class="inputs-container">
                <div class="input-container">
                    <div style="font-weight: 600">Location</div>
                    <div class="">
                        @foreach ($gasha_machines as $location)
                            @if($location->location_id == CRUDBooster::myLocationId()) 
                                <input type="text" class="form-control" style="border-radius: 5px; padding: 19px 10px 19px 10px;" name="location" id="location" value="{{ $location->location_name }}" readonly>
                                <input type="hidden" class="form-control" style="border-radius: 5px; padding: 19px 10px 19px 10px;" name="location_id" id="location_id" value="{{ $location->location_id }}" readonly>
                            @endif
                        @endforeach                 
                    </div>
                </div>

                <div class="input-container">
                    <div style="font-weight: 600">Bay</div>
                    <div class="custom-select" id="customSelectBay">
                        <select name="bay" id="bay"  required>
                            <option value="" disabled selected>Select Bay</option>
                            {{-- options will dynamically load here  --}}
                        </select>
                    </div>
                </div>
                <div id="bay_bnt_container" style="margin-top: 20px; display:none; padding: 3px">
                    {{-- dynamically load here if there's slected bay  --}}
                </div>
                
            </div>
          
            <table id="machine-table">
                <thead>
                    <tr>
                        <th>Machine Number</th>
                        <th>JAN #</th>
                        <th>Item Description</th>
                        <th>Number of Tokens</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5">
                            <div id="tableNote">
                                <br>
                                <img style="width: 50px;" src="https://cdn-icons-png.flaticon.com/128/3281/3281312.png" alt="">
                                <br><br>
                                <span class="text-info"><i class="fa fa-exclamation-circle"></i> Please select bay to filter data.</span>
                            </div>                        
                        </td>
                    </tr>
                </tbody>
                
                <tfoot>
                    <td colspan="4"><span><b>Total Quantity</b></span></td>
                    <td><input type="text" placeholder="0" name="total_qty" id="total_qty" style="border-radius: 5px; text-align: center; outline:none;" readonly></td>
                </tfoot>
            </table>
            
                <div style="font-weight: 600">Remarks</div>
                <div class="remarks_aria">
                    <textarea name="remarks" id="remarks" rows="4" class="form-control" style="border-radius: 7px;" placeholder="Enter your remarks here..." required></textarea>
                </div>
                <div style="margin-top: 10px;"><b>Note:</b> <b style="color: red">UNDECLARED</b> or <b style="color: red">MISDECLARED</b> Tokens found after Token Collection should be kept inside the Gasha machine and declared the next day.</div>
            </div>
        </form>
        
        <div class="form-button panel-footer" style="margin-top: 15px;" >
            <a class="btn-submit pull-left" href="{{ CRUDBooster::mainpath() }}" style="background:#838383; border: 1px solid #838383">Cancel</a>
            <button type="submit" class="btn-submit pull-right" id="btn-confirm-submit">Create</button>
        </div>
    </div>

    <div id="loadingBackdrop" class="loading-backdrop" style="display: none">
            <div class="spinner"></div>
    </div>

@endsection

@push('bottom')

<script>
    $('.content-header').hide();
    $(document).ready(function() {
        $('#customSelectLocation select').on('mousedown', function() {
            $('#customSelectLocation').toggleClass('open');
        });
        $('#customSelectLocation select').on('change', function() {
            $('#customSelectLocation').removeClass('open');
        });
        $('#customSelectLocation select').on('blur', function() {
            $('#customSelectLocation').removeClass('open');
        });

        $('#customSelectBay select').on('mousedown', function() {
            $('#customSelectBay').toggleClass('open');
        });
        $('#customSelectBay select').on('change', function() {
            $('#customSelectBay').removeClass('open');
        });
        $('#customSelectBay select').on('blur', function() {
            $('#customSelectBay').removeClass('open');
        });

        $('#auto-resize-textarea').on('input', function() {
            $(this).css('height', 'auto'); // Reset height
            $(this).css('height', this.scrollHeight + 'px'); // Set to scroll height
        });
    });

    $('#chat-button').on('click', function() {
        $(this).hide();
        $('#chat-container').show();
    });

    $('#chat-close').on('click', function() {
        $('#chat-container').hide();
        $('#chat-button').show();
    });

    // bays in gasha machine per loaction
    let location_baysData = @json($gasha_machines->mapWithKeys(function($item) {
        return [$item->location_id => $item->bays]; 
    }));

    // bays in submaster
    let bays_data = @json($gasha_machine_bay);

    $(document).ready(function() {
        let location_id = $('#location_id').val();

        if (location_id) {
            let location_bays = location_baysData[location_id] ? location_baysData[location_id].split(',') : [];

            $('#bay').empty();
            $('#bay').append('<option value="" disabled selected>Select Bay</option>');

            // check if bay is in locations bays
            let filteredBays = bays_data.filter(function(bay) {
                return location_bays.includes(bay.id.toString()); 
            });  
            
            if (filteredBays.length > 0) {
                filteredBays.forEach(function(bay) {
                    if (bay.get_collection_status && bay.get_collection_status.length > 0 && bay.get_collection_status[0]?.created_at) {
                        let get_created_at = bay.get_collection_status[0].created_at;
                        let created_at_date = new Date(get_created_at);
                        let formatted_created_at_date = created_at_date.toISOString().split('T')[0]; 

                        let date_now = new Date();
                        let formatted_date_now = date_now.toISOString().split('T')[0];

                        // Check if the created_at date is today
                        if (formatted_created_at_date === formatted_date_now) {
                            // if today dont show the bay option
                        } else {
                            if(bay.get_gasha_machine[0]?.bay_selected_by == {{CRUDBooster::myId()}}){
                                $('#bay').append('<option value="' + bay.id + '">' + bay.name + '</option>');
                                $('#bay_bnt_container').append('<button type="button" class="btn btn-danger" id="cancel_bay_btn" data-id="'+ bay.id +'" data-toggle="tooltip" data-placement="top" title="You selected '+ bay.name +', cancel it here if needed."> <i class="fa fa-times"></i> Cancel '+ bay.name +'</button>').show();                            
                            } else {
                                $('#bay').append('<option value="' + bay.id + '">' + bay.name + '</option>');
                            }
                        }

                    } else {
                        if(bay.get_gasha_machine[0]?.bay_selected_by == {{CRUDBooster::myId()}}){    
                            $('#bay').append('<option value="' + bay.id + '">' + bay.name + '</option>');
                            $('#bay_bnt_container').empty();
                            $('#bay_bnt_container').append('<button type="button" class="btn btn-danger" id="cancel_bay_btn" data-id="'+ bay.id +'" data-toggle="tooltip" data-placement="top" title="You selected '+ bay.name +', cancel it here if needed."> <i class="fa fa-times"></i> Cancel '+ bay.name +'</button>').show();                            
                        } else {
                            $('#bay').append('<option value="' + bay.id + '">' + bay.name + '</option>');
                        }
                    }
                });
            } else {
                $('#bay').append('<option value="" disabled>No bays available</option>');
            }
        } else {
            $('#bay').prop('disabled', true).empty().append('<option value="" disabled selected>Select Bay</option>');
        }

        $('#header_location_id').val(location_id);
    });

    $('#ggg').on('click', function(){
        alert('OH No.')
    });

    // request machines filteration on change bay 
    $('#bay').on('change', function(){
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        const location = $('#location_id').val();
        const bayValue = $('#bay').val();

        $('#loading').show();

        $.ajax({
            url: '{{route("getMachines")}}',
            method: 'POST',
            data: { 
                location: location,
                bay: bayValue,
                _token: csrfToken
            },
            success: function(response) {
                
                if (response && Array.isArray(response) && response.length > 0) {
                    $('#machine-table tbody').empty();

                    // Loop each machine & append value to table
                    response.forEach(function(machine) {
                        if (machine.bay_selected_by == {{ CRUDBooster::myId() }}) {
                            $('#bay_bnt_container').empty();
                            if (!$('#bay_bnt_container').find('[data-id="' + machine.bay + '"]').length) {
                                $('#bay_bnt_container').append(
                                    '<button type="button" class="btn btn-danger" id="cancel_bay_btn" data-id="' + machine.bay + '" data-toggle="tooltip" data-placement="top" title="You selected ' + machine.get_bay.name + ', cancel it here if needed."> <i class="fa fa-times"></i> Cancel ' + machine.get_bay.name + '</button>'
                                ).show();
                            }
                        }
                        
                        if(machine.bay_select_status == 1 && machine.bay_selected_by != {{ CRUDBooster::myId() }}){
                        
                            Swal.fire({
                                        icon: "warning",
                                        title: `<strong class='text-warning'> Unavailable <br> bay is currently selected by <br> <b class="text-info">${machine.get_bay_selector.name}</b></strong>`,
                                        showCloseButton: false,
                                        allowOutsideClick: false,  
                                        allowEscapeKey: false,
                                        confirmButtonColor: '#3c8dbc',
                                        allowEnterKey: true,
                                        confirmButtonText: `<i class="fa fa-thumbs- "></i> Got it!`,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            $('#bay').val('');
                                        }
                                    });

                            return; 
                        }
                        if (machine.get_inventory_item[0]?.get_inventory_capsule?.item?.digits_code && machine.get_inventory_item[0]?.get_inventory_capsule?.item?.item_description) {
                            let append = `
                                <tr data-location-id="${machine.location_id}" data-no-of-token="${machine.no_of_token}">
                                    <td>
                                        ${machine.serial_number}
                                        <input type="hidden" name="gasha_machines_id[]" value="${machine.id}" readonly>
                                    </td>
                                    <td>
                                        ${machine.get_inventory_item[0]?.get_inventory_capsule.item.digits_code ?? '<span style="color: darkorange"><i class="fa fa-warning"></i> Machine don`t have capsule</span>'}
                                    </td>
                                    <td>
                                        ${machine.get_inventory_item[0]?.get_inventory_capsule.item.item_description ?? '<span style="color: darkorange"><i class="fa fa-warning"></i> Machine don`t have capsule</span>'}
                                    </td>
                                    <td>
                                        ${machine.no_of_token}
                                        <input type="hidden" name="no_of_token[]" value="${machine.no_of_token}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" placeholder="Enter Quantity" class="qty-input" name="qty[]" style="border-radius: 5px; text-align: center; background-color: transparent;" oninput="this.value = this.value.replace(/[^0-9]/g, '');" autocomplete="off" required>
                                        <input type="hidden" name="location_id[]" value="${machine.location_id}" readonly>
                                        <input type="hidden" name="variance[]" id="variance" class="variance-input" readonly>
                                    </td>
                                </tr>
                            `;
                        
                            if(machine.get_collect_token_lines.length > 0) {
                                if(machine.get_collect_token_lines[0].collect_token_header.statuses_id == 5){
                                    $('#machine-table tbody').append(append);
                                } else {
                                    Swal.fire({
                                        icon: "warning",
                                        title: "<strong class='text-warning'> Unavailable <br> currently in collecting process.</strong>",
                                        showCloseButton: false,
                                        allowOutsideClick: false,  
                                        allowEscapeKey: false,
                                        allowEnterKey: true,
                                        confirmButtonText: `<i class="fa fa-thumbs-up"></i> Got it!`,
                                        html: `
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Location</th>
                                                        <th>Bay</th>
                                                        <th>Reference#</th>
                                                        <th>Collector</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>${machine.location_name}</td>
                                                        <td>${machine.get_bay.name}</td>
                                                        <td>${machine.get_collect_token_lines[0].collect_token_header.reference_number}</td>
                                                        <td>${machine.get_collect_token_lines[0].collect_token_header.get_created_by.name}</td>
                                                    <tr>
                                                </tbody>
                                            </table>
                                        `
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            $('#bay').val('');
                                        }
                                    });
                                }
                            } else {
                                $('#machine-table tbody').append(append);
                            }
                        }
                    });

                    $('#machine-table').on('input', '.qty-input', function() {
                        updateTotalQuantity(); 

                        const tokenCollected = $(this).val();
                        const NoOfToken = $(this).closest('tr').data('no-of-token');

                        // variance calculation
                        const divisionResult = tokenCollected / NoOfToken;
                        const ceilingResult = Math.ceil(divisionResult);
                        const multiplicationResult = ceilingResult * NoOfToken;
                        const finalResult = tokenCollected - multiplicationResult;

                        // Checking for variance
                        if (finalResult === 0) {
                            $(this).closest('tr').css('background-color', '');
                            $(this).closest('tr').find('input[name="variance[]"]').val('0'); 
                        } else {
                            $(this).closest('tr').css('background-color', '#f8d7da');
                            $(this).closest('tr').find('input[name="variance[]"]').val(finalResult); 
                        }

                        let allFinalResults = [];
                        let allZero = true;  

                            $('#machine-table tbody tr').each(function() {
                                const varianceValue = $(this).find('input[name="variance[]"]').val();
                                allFinalResults.push(varianceValue);

                                // If any value is not 0, set flag to false
                                if (varianceValue !== '0') {
                                    allZero = false;
                                }
                            });

                            if (allZero) {
                                $('#remarks').removeAttr('required');
                            } else {
                                $('#remarks').attr('required', true);
                            }
                    });

                    updateTotalQuantity();
                }
                $('#loading').hide();
            },
            error: function() {
                alert('Error Request!');
                $('#loading').hide(); 
            }
        });
        $('#header_bay_id').val(bayValue);
    });

    $(document).on('click', '#cancel_bay_btn', function() {
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
        let bay_id = $(this).data('id');
        let location_id = $('#header_location_id').val();

        $('#loading').show();

        $.ajax({
            url: '{{route("resetSelectedBay")}}',
            method: 'POST',
            data: { 
                bay_id: bay_id,
                location_id: location_id,
                _token: csrfToken
            },
            success: function(response) {
                $('#loading').hide(); 

                if (response.message) {
                    Swal.fire({
                        title: 'Successfully Canceled',
                        text: response.message,  
                        icon: 'success',  
                        confirmButtonText: 'OK',
                        allowOutsideClick: false,  
                        allowEscapeKey: false,
                        allowEnterKey: true,
                        confirmButtonText: `<i class="fa fa-thumbs- "></i> Okay`,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#bay').val('');
                                location.reload();
                            }
                    });
                } else {
                    alert('Operation was successful!');
                }
            },
            error: function(xhr, status, error) {
                alert('ERROR: Request error, Please check.');
                $('#loading').hide();
            }
        });
    });

    // update total qty
    function updateTotalQuantity() {
        let totalQuantity = 0;

        $('#machine-table .qty-input').each(function() {
            let qty = parseInt($(this).val()) || 0; 
            totalQuantity += qty;
        });

        $('#total_qty').val(totalQuantity);
    }

    $('#btn-confirm-submit').on('click', function(e) {
        e.preventDefault(); 
        const form = document.getElementById('collect_token');

        if (form.checkValidity()) {
            Swal.fire({
                title: "Are you sure you want to create collected token?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3C8DBC',
                cancelButtonColor: '#838383',
                confirmButtonText: 'Create',
                iconColor: '#3C8DBC',
                returnFocus: false,
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#loadingBackdrop').show();
                    form.submit(); 
                }
            });
        } else {
            form.reportValidity();
        }
    });
</script>
@endpush


