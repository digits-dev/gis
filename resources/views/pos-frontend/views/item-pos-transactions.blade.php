{{-- Please always check the current plugins, css, script in content.blade.php--}}

{{-- Extend the dashboard layout --}}
@extends('pos-frontend.components.content')

{{-- Your Plugins --}}
@section('plugins')
@endsection

{{-- Your CSS --}}
@section('css')
<style>
    :root {
    --primary: #e53e3e;
    --primary-dark: #c53030;
    --gray-100: #f7fafc;
    --gray-200: #edf2f7;
    --gray-300: #e2e8f0;
    --gray-600: #718096;
    --gray-800: #2d3748;
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --radius: 0.5rem;
}
.display {
    display: none;
}
.btn {
    margin: 5px
}
.styled-table-void {
margin: 25px 0;
font-size: 0.9em;
font-family: sans-serif;
min-width: 400px;
width: 60%;
text-align: center;
border-collapse: collapse;
margin: 0 auto;
font-weight: bold;
}
.styled-table-void td {
border: 1px solid #838383;
padding: 12px 15px;
}
.styled-table-void tr td:nth-of-type(odd) {
    color: #c02f2f;
}
.swal2-confirm {
    width: 95px;
}
.search-container {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    margin-bottom: 8px;
    position: relative;
}
.search-bar {
    padding: 10px 8px;
    border: 1px solid gray;
    border-radius: 10px;
    width: 400px;
    align-self: flex-end;
}
.search-btn-container {
    position: absolute;
    top: 5px;
    right: 4px;
}
.search-btn {
    padding: 6px 10px;
    background-color: #dd2a40;
    color: white;
    border-radius: 10px;
    cursor: pointer;
}
.search-btn:hover {
    filter: brightness(110%);
}
.swal-table-container {
display: flex;
gap: 20px;
}
.swal-container2 {
width: 70%;
}
.btn-details{
    background-color: #3c8dbc;
    padding: 1px 5.5px;
    border-radius: 2px;
    color: #fff;
    cursor: pointer;
}

.btn-void{
    background-color: #dd4b39;
    padding: 1px 5.5px;
    border-radius: 2px;
    color: #fff;
    font-size: 14px;
    cursor: pointer;
}

.swal2-confirm-red {
    background-color: #dd4b39 !important; /* red */
    color: white !important;
    border: none;
}
.bg-success{
    background-color: #00a65a;
    padding: 3px 5px;
    color: #fff;
    border-radius: 3px;
    font-size: 12px;
}
.bg-void{
    background-color: #dd4b39;
    padding: 3px 5px;
    color: #fff;
    border-radius: 3px;
    font-size: 12px;
}

/* MODAL */
.custom-modal {
    display: none; /* hidden by default */
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.5);
}

.custom-modal-content {
    background-color: #fff;
    margin: 10% auto;
    padding: 20px;
    border-radius: 8px;
    width: 50%;
    position: relative;
}

.custom-close {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 22px;
    font-weight: bold;
    cursor: pointer;
}

.custom-modal-footer {
    text-align: right;
    margin-top: 15px;
}

.hidden {
    display: none;
}



.items-section {
    margin-top: 20px;
}

.items-title {
    font-weight: bold;
    margin-bottom: 10px;
    font-size: 16px;
}

/* Responsive Design */
@media (max-width: 992px) {
    .custom-modal-content {
        width: 80%;
    }
}

@media (max-width: 768px) {
    .custom-modal-content {
        width: 90%;
        margin: 10% auto;
        padding: 20px;
    }
    
}

@media (max-width: 576px) {
    .custom-modal-content {
        width: 95%;
        margin: 5% auto;
        padding: 15px;
    }
    
    .custom-modal h3 {
        font-size: 18px;
    }
    
}

.transaction-header {
    margin-bottom: 2rem;
    background-color: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.transaction-title {
    background-color: var(--primary);
    color: white;
    padding: 1rem 1.5rem;
    font-size: 1.25rem;
    font-weight: 600;
}

.transaction-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    padding: 1.5rem;
}

.detail-group {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.detail-label {
    font-size: 0.875rem;
    color: var(--gray-600);
    font-weight: 500;
}

.detail-value {
    font-size: 1rem;
    font-weight: 600;
}

.styled-table-items{
    width: 100%;
    border-collapse: collapse;
    margin: 1.5rem 0;
    background-color: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.styled-table-items thead {
    background-color: var(--primary);
    color: white;
}
.styled-table-items th {
    text-align: left;
    padding: 1rem;
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.styled-table-items td {
    padding: 1rem;
    border-bottom: 1px solid var(--gray-200);
    font-size: 0.7rem;
}

.styled-table-items tbody tr:nth-child(even) {
    background-color: var(--gray-100);
}

.styled-table-items tbody tr:hover {
    background-color: var(--gray-200);
}

.styled-table-items tfoot {
    background-color: white;
}

.styled-table-items tfoot td {
    padding: 1rem;
    border-bottom: none;
}
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background-color: white;
    color: var(--gray-800);
    border: 1px solid var(--gray-300);
    padding: 0.75rem 1.25rem;
    border-radius: var(--radius);
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}
.btn-confirm {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background-color: var(--primary);
    color: var(--gray-100);
    border: 1px solid var(--gray-300);
    padding: 0.75rem 0.5rem;
    border-radius: var(--radius);
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}
.items-section{
    display: none;
}
#showDetails, #hideDetails {
    cursor: pointer;
}
</style>
@endsection

@section('content')

<div class="responsive_table">
<form action="/item_pos_transactions">
    <div class="search-container">
        <input
            class="search-bar"
            autofocus
            type="text"
            name="search"
            placeholder="Search"
        />
        <div class="search-btn-container">
            <button
            class="search-btn"
                type="submit"
            >
                Search
            </button>
        </div>
    </div>
</form>
<table id="myTable">
    <thead>
        <tr>
            @foreach($table_header as $key => $header)
                <th @if($key == 'action') style="text-align: center" @endif>
                    {{ $header }}
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($item_pos as $item)
            <tr>
                {{-- View button --}}
                <td style="display: flex; align-items:center; justify-content: center">
                    <a class="btn btn-details" href="item_pos_transactions/show/{{ $item['id'] }}""><i class="fa-solid fa-eye"></i></a>
                    @if($item['status'] != "VOID" && date('Y-m-d', strtotime($item['created_at'])) == date('Y-m-d'))
                        <a class="btn btn-void" data-id={{$item['id']}} id="btnVoid">
                            @if (in_array(auth()->user()->id_cms_privileges, [1,5,15]))
                                <i class="fa-solid fa-times-circle"></i>
                            @endif            
                        </a>
                    @endif
                </td>

                @foreach($item as $key => $value)
                    @if(!in_array($key, ['id']))
                        <td>
                            @if($key === 'status')
                                <span class="{{$value === 'VOID' ? 'bg-void' : 'bg-success'}}">
                                    {{ strtoupper($value) }}
                                </span>
                            @else
                                {{ $value }}
                            @endif
                        </td>
                    @endif
                @endforeach
            </tr>
        @endforeach
        
    </tbody>
    <tfoot>
        <tr>
            @foreach($table_header as $key => $header)
                <th @if($key == 'action') style="text-align: center" @endif>
                    {{ $header }}
                </th>
            @endforeach
        </tr>
    </tfoot>
</table>
{{ $item_pos->links() }}
</div>
<!-- Void Confirmation Modal -->
<div id="customVoidModal" class="custom-modal hidden">
<div class="custom-modal-content">
    <div id="customModalBody">
        <!-- Dynamic content inserted here -->
    </div>
    <div class="custom-modal-footer">
        <button class="btn-back" id="cancelModal">
            <i class="fa fa-circle-arrow-left"></i>
            Cancel</button>
        <button class="btn btn-confirm" id="confirmVoidBtn"><i class="fa fa-thumbs-up"></i>Confirm Void</button>
    </div>
</div>
</div>

@endsection

{{-- Your Script --}}
@section('script-js')
<script src="{{ asset('plugins/sweetalert.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function () {
    $('#myTable').DataTable({
        "order": [[9, 'desc']],
        "bPaginate": false,
        "bFilter": false,
        "bInfo": false,
    });
});

$(document).ready(function() {
    let currentVoidId = null;

        $(document).on('click', '#btnVoid', function (e) {
            e.preventDefault();

            currentVoidId = $(this).data('id');
            $.ajax({
                type: 'GET',
                url: `item_pos_transactions/getDetail/${currentVoidId}`,
                success: function (res) {
                    console.log(res.items);
                    const data = res.items;
                    
                    // Transaction details table
                    const detailsHtml = `
                        <div class="transaction-header">
                        <div class="transaction-title">
                            <i class="fa fa-exclamation-circle"></i> Please check transaction details before voiding
                        </div>
                        <div class="transaction-details">
                        <div class="detail-group">
                            <div class="detail-item">
                            <span class="detail-label">Reference #</span>
                            <span class="detail-value">${data.reference_number}</span>
                            </div>
                            <div class="detail-item">
                            <span class="detail-label">Total Value</span>
                            <span class="detail-value highlight">${data.total_value}</span>
                            </div>
                            
                        </div>
                            <div class="detail-group">
                            <div class="detail-item">
                                <span class="detail-label">Change Value</span>
                                <span class="detail-value">${data.change_value}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Mode of Payment</span>
                                <span class="detail-value">${data.mode_of_payments.payment_description}</span>
                            </div>
                            </div>
                        </div>
                    </div>
                    `;
                    
                    // Check if item_lines exists and has items
                    let itemsHtml = '';
                    if (data.item_lines && data.item_lines.length > 0) {
                        const excludedKeys = ['item_pos_id', 'id', 'locations_id','status','created_by','created_at','updated_by','updated_at','deleted_at'];
                        const headers = Object.keys(data.item_lines[0]).filter(key => !excludedKeys.includes(key));

                        let tableHeaders = '';
                        headers.forEach(header => {
                            const formattedHeader = header.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
                            tableHeaders += `<th>${formattedHeader}</th>`;
                        });

                        let tableRows = '';
                        data.item_lines.forEach(item => {
                            let row = '<tr>';
                            headers.forEach(key => {
                                row += `<td>${item[key] || ''}</td>`;
                            });
                            row += '</tr>';
                            tableRows += row;
                        });
                        
                        // Addons section logic
                        let addonHtml = '';
                        if (res.addons && res.addons.length > 0) {
                            const addonHeaders = Object.keys(res.addons[0]);
                            let addonHeaderHtml = '';
                            addonHeaders.forEach(header => {
                                const formatted = header.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
                                addonHeaderHtml += `<th>${formatted}</th>`;
                            });

                            let addonRowHtml = '';
                            res.addons.forEach(addon => {
                                let row = '<tr>';
                                addonHeaders.forEach(key => {
                                    row += `<td>${addon[key] || ''}</td>`;
                                });
                                row += '</tr>';
                                addonRowHtml += row;
                            });

                            addonHtml = `
                                <div class="items-section">
                                    <h3>Addons</h3>
                                    <table class="styled-table-items">
                                        <thead>
                                            <tr>${addonHeaderHtml}</tr>
                                        </thead>
                                        <tbody>
                                            ${addonRowHtml}
                                        </tbody>
                                    </table>
                                </div>
                            `;
                        }

                        itemsHtml = `
                            <div id="showDetails"><i class="fa fa-circle-chevron-down"></i> View details</div>
                            <div id="hideDetails" style="display: none;"><i class="fa fa-circle-chevron-up"></i> Hide details</div>
                            <div class="items-section" style="overflow-x: auto;">
                                <table class="styled-table-items">
                                    <thead>
                                        <tr>${tableHeaders}</tr>
                                    </thead>
                                    <tbody>
                                        ${tableRows}
                                    </tbody>
                                </table>
                                ${addonHtml} <!-- Addons HTML goes directly under items -->
                            </div>
                        `;
                    }


                    // Combine both tables
                    $('#customModalBody').html(detailsHtml + itemsHtml);
                    $('#customVoidModal').show();
                },
                error: function (err) {
                    console.error(err);
                }
            });
        });

        $('#customModalClose, #cancelModal').on('click', function () {
            $('#customVoidModal').hide();
        });

        $(document).on('click', '#showDetails', function () {
            $('.items-section').show();
            $('#showDetails').hide();
            $('#hideDetails').show();
        });

        $(document).on('click', '#hideDetails', function () {
            $('.items-section').hide();
            $('#showDetails').show();
            $('#hideDetails').hide();
        });
       
        $('#confirmVoidBtn').on('click', function () {
            Swal.fire({
                title: "Are you sure?",
                text: "You are about to void this transaction.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "Cancel",
                reverseButtons: true,
                allowOutsideClick: false,
                customClass: {
                    confirmButton: 'swal2-confirm-red'
                }
            }).then((result) => {
                if (result.isConfirmed && currentVoidId) {
                    $.ajax({
                        type: 'GET',
                        url: `item_pos_transactions/void/${currentVoidId}`,
                        success: function (res) {
                            if (res.type === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: res.message,
                                }).then(() => location.reload());
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: res.message,
                                });
                            }
                        },
                        error: function (err) {
                            console.error(err);
                        }
                    });
                }
                $('#customVoidModal').hide();
            });
        });

    });
</script>
@endsection