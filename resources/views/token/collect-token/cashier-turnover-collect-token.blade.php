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

    /* TABLE */

    .table-wrapper {
        overflow-x: auto;
        margin-bottom: 10px;

    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0 10px 0;
        font-size: 12px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: center;
    }

    th:nth-child(10){
        position: sticky;
        right: 0; 
        background-color: #3C8DBC; 
        z-index: 2;
        box-shadow: -1px 0 2px rgba(0, 0, 0, 0.1);
    }

    td:nth-child(10){
        position: sticky;
        right: 0; 
        background-color: white; 
        z-index: 2; /* Ensures it stays above other content */
        box-shadow: -1px 0 2px rgba(0, 0, 0, 0.1);
    }

    th {
        background-color: #3C8DBC;
        color: white;
        font-weight: bold;
        white-space: nowrap;
        width: auto; 
        
    }

      /* Make the specific headers sticky */
    th.sticky-header {
        position: sticky;
        top: 0;
        z-index: 1; /* Ensures they stay above other content */
        background-color: #3C8DBC; /* Match the table header color */
    }

    /* Add this style for smooth scrolling and avoid overlap */
    .custom-scroll-x {
        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;
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

    .no-variance-type {
        background: linear-gradient(to right, #53FF14, #2C8624);
        border: none;
        color: white;
        padding: 10px 20px;
        white-space: nowrap;
        border-radius: 10px;
        font-weight: bold;
    }

    .short-type {
        background: linear-gradient(to right, #FF1414, #780E0E);
        border: none;
        color: white;
        padding: 10px 20px;
        white-space: nowrap;
        border-radius: 10px;
        font-weight: bold;
    }
    
    .over-type {
        background: linear-gradient(to right, #FA922A, #AD5600);
        border: none;
        color: white;
        padding: 10px 20px;
        white-space: nowrap;
        border-radius: 10px;
        font-weight: bold;
    }

    /* X SCROLLBAR */

    .custom-scroll-x {
    overflow-x: auto; 
    overflow-y: hidden;
    white-space: nowrap;
    }

    .custom-scroll-x::-webkit-scrollbar {
    height: 8px; 
    }

    .custom-scroll-x::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .custom-scroll-x::-webkit-scrollbar-thumb {
        background-color: #4db2ec;
        border-radius: 10px;
        border: 2px solid #f1f1f1; 
    }

    .custom-scroll-x::-webkit-scrollbar-thumb:hover {
    
    }

</style>
@endpush
@section('content')
<form class="panel panel-default form-content">
    <div class="panel-heading header-title text-center">Collect Token Details</div>
    <div class="content-panel">
        <div class="inputs-container" style="margin-bottom: 10px;">
            <div class="input-container">
                <div style="font-weight: 600">Reference Number</div>
                <input type="text" style="border-radius: 5px;" disabled>
            </div>

            <div class="input-container">
                <div style="font-weight: 600">Location</div>
                <input type="text" style="border-radius: 5px;" disabled>
            </div>
        </div>
        <div class="inputs-container">
            <div class="input-container">
                <div style="font-weight: 600">Total Quantity</div>
                <input type="text" style="border-radius: 5px;" disabled>
            </div>

            <div class="input-container">
                <div style="font-weight: 600" >Date Created</div>
                <input type="text" style="border-radius: 5px;" disabled>
            </div>
        </div>
        
        <div class="table-wrapper custom-scroll-x">
            <table>
                <thead>
                    <tr>
                        <th>Machine #</th>
                        <th>JAN #</th>
                        <th>No of Token</th>
                        <th>Token Collected</th>
                        <th>Variance</th>
                        <th>Projected Capsule Sales</th>
                        <th>Current Machine Inventory</th>
                        <th>Actual Capsule Inventory</th>
                        <th>Actual Capsule Sales</th>
                        <th>Variance Type</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>M-00000001</td>
                        <td>4570118211583</td>
                        <td>4</td>
                        <td>10</td>
                        <td>2</td>
                        <td>3</td>
                        <td>5</td>
                        <td><input type="text" placeholder="Enter Quantity"></td>
                        <td></td>
                        <td><span class="no-variance-type">No Variance</span></td>
                    </tr>
                    <tr>
                        <td>M-00000002</td>
                        <td>4570118211583</td>
                        <td>4</td>
                        <td>10</td>
                        <td>2</td>
                        <td>3</td>
                        <td>5</td>
                        <td><input type="text" placeholder="Enter Quantity"></td>
                        <td></td>
                        <td><span class="short-type">Short</span></td>
                    </tr>
                    <tr>
                        <td>M-00000003</td>
                        <td>4570118211583</td>
                        <td>4</td>
                        <td>10</td>
                        <td>2</td>
                        <td>3</td>
                        <td>5</td>
                        <td><input type="text" placeholder="Enter Quantity"></td>
                        <td></td>
                        <td><span class="over-type">Over</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
     
  
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

        $(function(){
            $('body').addClass("sidebar-collapse");
        });

    });
</script>
@endpush