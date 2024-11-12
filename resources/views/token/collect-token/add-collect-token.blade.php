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

</style>
@endpush
@section('content')
    <form class="panel panel-default form-content">
        <div class="panel-heading header-title text-center">Add Collect Token Form</div>
        <div class="content-panel">
            <div class="inputs-container">
                <div class="input-container">
                    <div style="font-weight: 600">Location</div>
                    <div class="custom-select" id="customSelectLocation">
                        <select>
                            <option value="" disabled selected>Select Location</option>
                            <option value="location1">Location 1</option>
                            <option value="location2">Location 2</option>
                            <option value="location3">Location 3</option>
                        </select>
                    </div>
                </div>

                <div class="input-container">
                    <div style="font-weight: 600">Bay</div>
                    <div class="custom-select" id="customSelectBay">
                        <select>
                            <option value="" disabled selected>Select Bay</option>
                            <option value="location1">Bay 1</option>
                            <option value="location2">Bay 2</option>
                            <option value="location3">Bay 3</option>
                        </select>
                    </div>
                </div>
                
            </div>
          
            <table>
                <thead>
                    <tr>
                        <th>Machine Number</th>
                        <th>Number of Tokens</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>M-00000001</td>
                        <td>7</td>
                        <td><input type="text" placeholder="Enter Quantity"></td>
                    </tr>
                </tbody>
            </table>
         
      
            <div class="input-container">
                <div style="font-weight: 600; margin-bottom:4px;">Remark/s</div>
                <textarea id="remarks" rows="2" placeholder="Add Remark here"></textarea>
            </div>
            
            
            
            <div class="form-button" style="margin-top: 15px;" >
                <a class="btn-submit pull-left" href="{{ CRUDBooster::mainpath() }}" style="background:#838383; border: 1px solid #838383">Cancel</a>
                <button class="btn-submit pull-right" id="btn-submit">Confirm</button>
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
</script>
@endpush


