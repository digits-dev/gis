@extends('crudbooster::admin_template')
@push('head')
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

    textarea {
        width: 100%;
        padding: 12px;
        box-sizing: border-box;
        border: 1px solid #3C8DBC; 
        border-radius: 4px;
        outline-color: #3C8DBC;
        font-size: 14px;
        resize: vertical;
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


</style>
@endpush
@section('content')
    <form class="panel panel-default form-content" method="POST" action="{{route('postCollectedToken')}}">
        @csrf
        <input type="hidden" name="header_location_id" id="header_location_id" readonly>
        <div class="panel-heading header-title text-center">Add Collect Token Form</div>
        <div class="content-panel">
            <div class="inputs-container">
                <div class="input-container">
                    <div style="font-weight: 600">Location</div>
                    <div class="custom-select" id="customSelectLocation">
                        <select name="location" id="location" required>
                            <option value="" disabled selected>Select Location</option>
                            @foreach ($gasha_machines as $location)
                                <option value="{{ $location->location_id }}">{{$location->location_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="input-container">
                    <div style="font-weight: 600">Bay</div>
                    <div class="custom-select" id="customSelectBay">
                        <select name="bay" id="bay" disabled required>
                            <option value="" disabled selected>Select Bay</option>
                        </select>
                    </div>
                </div>
                
            </div>
          
            <table id="machine-table">
                <thead>
                    <tr>
                        <th>Machine Number</th>
                        <th>Number of Tokens</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3">
                            <div id="loading" style="display: none;">
                                <div class="spinner-overlay">
                                    <div class="spinner"></div> 
                                </div>
                                <div style="margin-top: 18px;" id="tableNote">
                                    <span>Loading, Please wait.</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
                
                <tfoot>
                    <td colspan="2"><span><b>Totay Quantity</b></span></td>
                    <td><input type="text" placeholder="0" name="total_qty" id="total_qty" style="border-radius: 5px; text-align: center" readonly></td>
                </tfoot>
            </table>
         
      
            <div class="input-container">
                <div style="font-weight: 600; margin-bottom:4px;">Remark/s</div>
                <textarea id="remarks" rows="2" placeholder="Add Remark here"></textarea>
            </div>
            
            <div class="form-button" style="margin-top: 15px;" >
                <a class="btn-submit pull-left" href="{{ CRUDBooster::mainpath() }}" style="background:#838383; border: 1px solid #838383">Cancel</a>
                <button type="submit" class="btn-submit pull-right" id="btn-submit">Confirm</button>
            </div>
        </div>
    </form>


@endsection

@push('bottom')
<script>
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
    });

    // filtered bay base on location
    let baysData = @json($gasha_machines->mapWithKeys(function($item) {
        return [$item->location_id => $item->bays]; 
    }));

    $('#location').change(function() {
        let locationName = $(this).val();
        if (locationName) {
            let bays = baysData[locationName] ? baysData[locationName].split(',') : [];

            $('#bay').prop('disabled', false);

            $('#bay').empty();
            $('#bay').append('<option value="" disabled selected>Select Bay</option>');

            if (bays.length > 0) {
                $.each(bays, function(index, bay) {
                    $('#bay').append('<option value="' + bay.trim() + '">' + bay.trim() + '</option>');
                });
            } else {
                $('#bay').append('<option value="" disabled>No bays available</option>');
            }
        } else {
            $('#bay').prop('disabled', true).empty().append('<option value="" disabled selected>Select Bay</option>');
        }
        $('#header_location_id').val(locationName);
    });
    // end filteration here

    // request machines filteration on change bay 
    $('#bay').on('change', function(){
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        const location = $('#location').val();
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
                        let append = `
                            <tr data-location-id="${machine.location_id}" data-no-of-token="${machine.no_of_token}">
                                <td>
                                    ${machine.serial_number}
                                    <input type="hidden" name="gasha_machines_id[]" value="${machine.id}" readonly>
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
                        $('#machine-table tbody').append(append);
                    });

                    $('#machine-table').on('input', '.qty-input', function() {
                        updateTotalQuantity(); 

                        const tokenCollected = $(this).val();
                        const NoOfToken = $(this).closest('tr').data('no-of-token');

                        // variance calculation
                        const divisionResult = tokenCollected / NoOfToken;
                        const ceilingResult = Math.ceil(divisionResult);
                        const multiplicationResult = ceilingResult * NoOfToken;
                        const finalResult = multiplicationResult - tokenCollected;

                        // Checking for variance
                        if (finalResult === 0) {
                            $(this).closest('tr').css('background-color', '');
                            $(this).closest('tr').find('input[name="variance[]"]').val('0'); 
                        } else {
                            $(this).closest('tr').css('background-color', '#f8d7da');
                            $(this).closest('tr').find('input[name="variance[]"]').val(finalResult); 
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

</script>
@endpush


