{{-- Please always check the current plugins, css, script in content.blade.php--}}

{{-- Extend the dashboard layout --}}
@extends('pos-frontend.components.content')
{{-- Title of the page --}}

<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/item-pos.css') }}">
</head>


{{-- Define the content to be included in the 'content' section --}}
@section('content')
    <div class="container">
        <div class="main-container" >
            {{-- CONTAINER 1 --}}
            <div class="container-1">
                <form class="scanner-container" id="scanner_form">
                    <div class="scanner-container-child1">
                        <p>Scan JAN Code</p>
                        <button class="active-scanner-button">
                            <img class="scanner-icon" src="{{ asset('img/item-pos/item-pos-scanner.png') }}" alt="">
                            <p>Open Camera Scanner</p>
                        </button>
                    </div>
                    <div class="scanner-container-child2">
                        <div style="flex:1;">
                            <input id="jan-code-input" class="scan-input" type="text" placeholder="Enter JAN Code Here"/>
                            <div style="display: flex; justify-content: space-between">
                                <p style="font-size: 12px; font-weight: 500; color: #747474;">For Manual Code Input use only</p>
                                <div class="quantity-wrapper">
                                    <span style="font-size: 14px; font-weight: 500">Quantity</span>
                                    <button type="button" class="quantity-btn minus">-</button>
                                    <span class="quantity-number">1</span>
                                    <button type="button" class="quantity-btn plus">+</button>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="scan-btn" class="scan-button">
                            <p>Scan</p>
                        </button>
                    </div>
                </form>
                <div class="items-container">
                    <div style="display: flex;  align-items: end; justify-content: space-between">
                        <div style="display:flex; align-items: center; gap: 8px;">
                            <img style="width: 20px; height: auto" src="{{ asset('img/item-pos/item-pos-item-icon.png') }}" alt="">
                            <span style="font-weight: 600">Item Details</span>
                        </div>
                        <p id="total-items" style="font-size: 12px; font-weight: 500; color: #747474;">0 items</p>
                    </div>
                    <div class="item-container" style="width: 100%; height: 100%; overflow-y: auto; max-height: 400px; scrollbar-width: none;">
                        {{-- ITEMS WILL GO HERE --}}
                    </div>
                </div>
            </div>
            {{-- CONTAINER 2 --}}
            <div class="container-2">
                <div class="items-container">
                    <div style="display:flex; align-items: center; gap: 10px;">
                        <img style="width: 25px; height: auto" src="{{ asset('img/item-pos/gashapon-logo.png') }}" alt="">
                        <span style="font-weight: 700;">Checkout</span>
                    </div>
                    <div style="margin-top: 5px;">
                        <label class="payment-label">
                            <img src="{{ asset('img/item-pos/item-pos-payment.png') }}" alt="wallet" width="20">
                            Payment Method
                        </label>
                        <select class="payment-select" id="paymentMethod" style="height: 40px;">
                            <option disabled selected>Choose Payment Method</option>
                            @foreach ($mode_of_payments as $mode_of_payment )
                            <option value="{{ $mode_of_payment->id  }}">{{ $mode_of_payment->payment_description  }}</option>                      
                            @endforeach
                        </select>
                    </div>
                    <div style="margin-top: 10px; display: none" id="amount-wrapper">
                        <label for="amount" class="amount-label">Amount</label>
                        <input type="text" name="amount" class="amount-input" placeholder="Enter Amount">
                    </div>    
                    <div style="margin-top: 10px; display: none" id="reference-wrapper">
                        <label for="reference" class="reference-label">Reference Number</label>
                        <input type="text" name="reference" class="reference-input" placeholder="Enter Reference Number">
                    </div>    
                    <div class="items-container" style="margin-top: 10px; font-size: 14px; gap:5px">
                        <div style="display: flex; justify-content: space-between">
                            <p style="font-weight: 600">Total</p>
                              <p>₱<span class="total-value">0.00</span></p>
                        </div>
                        <div class="change-amount-details" style="display:none">
                            <div style="display: flex; justify-content: space-between">
                                <p style="font-weight: 600">Amount</p>
                                <p>₱<span class="amount-value">0.00</span></p>
                            </div>
                            <div style="border-top: 1px dashed black; margin: 4px 0;"></div>
                            <div style="display: flex; justify-content: space-between">
                                <p style="font-weight: 600">Change</p>
                                  <p>₱<span class="change-value">0.00</span></p>
                            </div>
                        </div>
                    </div>
                    <button class="scan-button" id="process-button" style="margin-top: 12px">
                        <p>Process Payment</p>
                    </button>            
                    <button class="clear-button" style="margin-top: 12px">
                        <p>Clear Cart</p>
                    </button>            
                </div>
                <div class="quick-guide-wrapper">
                    <p style="font-weight: 600; margin-bottom: 3px; font-size: 14px">Quick Guide</p>
                    <p style="font-size: 10px">1. Scan or Enter JAN Code to add Items</p>
                    <p style="font-size: 10px">2. Double Check Scanned Items</p>
                    <p style="font-size: 10px">3. Adjust quantities as needed</p>
                    <p style="font-size: 10px">4. Add Payment Method</p>
                    <p style="font-size: 10px">5. Enter Amount to pay</p>
                    <p style="font-size: 10px">6. Proceed transaction when finished</p>
                </div>
            </div>
        </div>
    </div>
    <div id="loadingSpinnerModal" style="display: none;">
        <div style="
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        ">
            <div class="spinner"></div>
        </div>
    </div>
@endsection

{{-- Your Script --}}

@section('script-js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<script>
    $(document).ready(function() {

        $(".container").fadeIn(1000);
        
        // ---------------------------------------------------- INITIALIZATION -----------------------------------------------//

        let cartItems = [];

        //  ----------------------------------------------------- MODAL -------------------------------------------------------//
        function showSpinner() {
            $('#loadingSpinnerModal').fadeIn(200);
        }

        function hideSpinner() {
            $('#loadingSpinnerModal').fadeOut(200);
        }

        //  ------------------------------------------------------- FOR SCANNING JAN CODE ---------------------------------------------/

        let isSubmitting = false;

        $('#scan-btn').on('click', function() {
            $(this).prop('disabled', true);
            $('#scanner_form').submit();
        });

        $('#scanner_form').on('submit', function (e) {
            e.preventDefault();

            if (isSubmitting) return;
            isSubmitting = true;

            $('#scan-btn').text('Scanning...');

            const janCode = $('#jan-code-input').val();
            const ScannedQty = $('.quantity-number').text();

            if (janCode.trim() === '') {
                Swal.fire({
                    icon: 'info',
                    title: 'Missing JAN Code',
                    confirmButtonText: 'OK',
                    text: 'Please enter a JAN code before scanning.',
                    customClass: {
                        confirmButton: 'my-swal-btn'
                    },
                });
                $('#scan-btn').prop('disabled', false);
                $('#scan-btn').text('Scan');
                isSubmitting = false;
                return;
            }

            showSpinner();

            $.ajax({
                url: "{{ route('check.jan.code') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    jan_code: janCode,
                    scan_qty: ScannedQty,
                },
                success: function (response) {
                    if (response.status == 'found') {
                        addToCart(response.item);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: response.status === 'not_found' ? 'Item Not Found' : 'Item Insufficient Stock',
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'my-swal-btn'
                            },
                        });
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Scan Failed',
                        text: 'There was an error processing the JAN code.',
                        customClass: {
                            confirmButton: 'my-swal-btn'
                        },
                    });
                },
                complete: function () {
                    hideSpinner();
                    $('#jan-code-input').val('');
                    $('#scan-btn').prop('disabled', false);
                    $('#scan-btn').text('Scan');
                    $('.quantity-number').text('1');
                    isSubmitting = false;
                }
            });
        });



        $('.quantity-btn').click(function() {
            const $quantity = $(this).siblings('.quantity-number');
            const currentVal = parseInt($quantity.text());
            
            if ($(this).hasClass('plus')) {
                $quantity.text(currentVal + 1);
            } else if ($(this).hasClass('minus')) {
                if (currentVal > 1) { // Prevent going below 1
                    $quantity.text(currentVal - 1);
                }
            }

        });

        // ----------------------------------------------------- PAYMENT METHOD -----------------------------------------------------//

        $('#paymentMethod').change(function() {
            const selected = $(this).val();

            const amountDetails = $('.change-amount-details');
            const amountWrapper = $('#amount-wrapper');
            const amountInput = $('.amount-input');
            const referenceWrapper = $('#reference-wrapper');
            const referenceInput = $('.reference-input');

            if (selected == 1) {
                amountWrapper.fadeIn(1000);
                amountDetails.show();
                referenceWrapper.hide();
                referenceInput.val('');
            } else {
                amountDetails.hide();
                amountWrapper.hide();
                amountInput.val('');
                referenceWrapper.fadeIn(1000);
            }
        });


        // ----------------------------------------------------- FOR DETAILS UI -----------------------------------------------------//

        function formatCurrency(num) {
            return new Intl.NumberFormat('en-PH', {
                style: 'decimal',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(num);
        }

        function formatWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        $('.amount-input').on('input', function() {
            let rawInput = $(this).val().replace(/[^0-9]/g, '');

            // Prevent leading zero unless it's the only digit
            if (rawInput.length > 1 && rawInput.startsWith('0')) {
                rawInput = rawInput.replace(/^0+/, '');
            }

            // Format with commas for display
            let formattedInput = formatWithCommas(rawInput);
            $(this).val(formattedInput);

            // Convert to number for calculations
            let amount = parseFloat(rawInput || 0);

            $('.amount-value').text(formatCurrency(amount));

            let totalText = $('.total-value').text().replace(/,/g, '');
            let total = parseFloat(totalText || 0);

            let change = amount - total;
            if (change < 0) change = 0;

            $('.change-value').text(formatCurrency(change));
        });

        // ------------------------------------------------------- CLEAR CART ----------------------------------------------//

        $('.clear-button').on('click', function () {
            Swal.fire({
                title: 'Clear Cart?',
                text: 'Are you sure you want to remove all items from the cart?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, clear it!',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'my-swal-btn',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    cartItems = [];              
                    renderCart();                  
                    updateTotalDisplay();          
                    $('.amount-input').val('');     
                    $('.reference-input').val('');     
                    $('.amount-value').text('0.00');
                    $('.change-value').text('0.00');
                    $('.change-amount-details').hide();
                    $('#amount-wrapper').hide();
                    $('#reference-wrapper').hide();
                    $('#jan-code-input').val('');
                    $('#paymentMethod').prop('selectedIndex', 0);
                }
            });
        });

        // ------------------------------------------------------- FOR ITEMS -------------------------------------------------- //

        renderCart();

        function addToCart(newItem) {
            const existingItem = cartItems.find(item => item.code === newItem.code);
            if (existingItem) {
                if (existingItem.quantity + newItem.quantity > existingItem.item_stock_qty){
                    Swal.fire({
                        icon: 'error',
                        title: 'Item Insufficient Stock',
                        text: `Quantity exceeds available stock. Max stock: ${existingItem.item_stock_qty}`,
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'my-swal-btn'
                        },
                    });
                    return;
                }

                existingItem.quantity += newItem.quantity;
                existingItem.subtotal = existingItem.quantity * existingItem.price;
            } else {
                const maxId = cartItems.length > 0 ? Math.max(...cartItems.map(item => item.id)) : 0;
                newItem.id = maxId + 1;
                newItem.subtotal = newItem.price * newItem.quantity; 
                cartItems.push(newItem);
            }

            renderCart();
            updateTotalDisplay();
            updateChangeDisplay();
            
        }

        function renderCart() {
            let html = '';

            if (cartItems.length === 0) {
                html = `
                    <div style="display: flex; flex-direction: column; gap:10px; user-select: none; justify-content: center; align-items: center; border: 1px solid #c7c7c7; height: 100%; border-style: dashed; border-radius: 8px;">
                        <img style="width: 80px; height: auto; opacity: .7;" src="{{ asset('img/item-pos/item-pos-out-of-stock.png') }}" alt="">
                        <p style="font-size: 18px; font-weight: 500; color: #c7c7c7;">Scanned items will go here</p>
                    </div>
                `;

                $('.clear-button').prop('disabled', true);
            } else {

                cartItems.forEach((item) => {
                    const formattedSubtotal = formatCurrency(item.subtotal);

                    html += `
                        <div class="cart-item" data-id="${item.id}" data-stock="${item.item_stock_qty}">
                            <div class="cart-item-wrapper1">
                                <div class="item-details">
                                    <p style="font-size:12px; font-weight: 600;">${item.name}</p>
                                    <p style="font-size:12px; font-weight: 600; color: #A3A3A3">${item.code}</p>
                                    <p style="font-size:12px; font-weight: 600;">₱${item.price}</p>
                                </div>
                                <div style="display:flex; flex-direction: column; align-items:end">
                                    <div class="item-controls">
                                        <button class="qty-minus">-</button>
                                        <input type="text" class="qty" value="${item.quantity}" readonly>
                                        <button class="qty-plus">+</button>
                                    </div>
                                    <div class="subtotal" style="text-align:end; margin-top:5px;">
                                        <p style="color: #A3A3A3; font-size:10px; font-weight: 600;">Subtotal</p>
                                        <div>
                                            <span>₱</span>
                                            <span class="subtotal-amount" style="font-weight: 600;">${formattedSubtotal}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="border-top: 1px solid red; margin: 5px 0"></div>
                            <div style="display: flex; flex-direction: row; justify-content: space-between;">
                                <div class="item_stock_qty" style="font-size:12px">Stock: ${item.item_stock_qty}</div>
                                <button class="remove-btn" style="display:flex; align-items:center; background: white; cursor:pointer;">
                                    <img style="width: 14px; height: auto" src="{{ asset('img/item-pos/item-pos-trash-can.png') }}" alt="">
                                    <span style="font-size: 12px; margin-left:5px;">Remove</span>
                                </button>
                            </div>
                        </div>
                    `;
                });

                $('.clear-button').prop('disabled', false);
            }

            $('.item-container').html(html);
            $('#total-items').text(`${cartItems.length} items`);
            attachEvents();

        }

        function updateTotalDisplay() {
            const total = cartItems.reduce((sum, item) => sum + item.subtotal, 0);
            $('.total-value').text(formatCurrency(total));
        }

        function updateSubtotal(parent) {
            const id = parent.data('id');
            const item = cartItems.find(x => x.id === id);

            if (item) {
                const qty = parseInt(parent.find('.qty').val());
                item.quantity = qty;
                item.subtotal = qty * item.price;
                parent.find('.subtotal-amount').text(formatCurrency(item.subtotal));
                updateTotalDisplay();
            }
        }

        // PLUS AND MINUS OF EACH ITEM

        function updateChangeDisplay() {
            const amountRaw = $('.amount-input').val().replace(/,/g, '').replace(/[^0-9.]/g, '');
            const amount = parseFloat(amountRaw || 0);

            const totalText = $('.total-value').text().replace(/,/g, '');
            const total = parseFloat(totalText || 0);

            const change = amount - total;
            $('.change-value').text(formatCurrency(change < 0 ? 0 : change));
            $('.amount-value').text(formatCurrency(amount));
        }

        function attachEvents() {
            $('.qty-plus').off('click').on('click', function(){
                const parent = $(this).closest('.cart-item');
                const qtyInput = parent.find('.qty');
                const quantity = parseInt(qtyInput.val());
                const itemStockQty = parseInt(parent.data('stock'));

                if (quantity + 1 > itemStockQty) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Item Insufficient Stock',
                        text: `Quantity exceeds available stock. Max stock: ${itemStockQty}`,
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'my-swal-btn'
                        },
                    });
                    return;
                }

                qtyInput.val(quantity + 1);
                updateSubtotal(parent);
                updateChangeDisplay();
                
            });

            $('.qty-minus').off('click').on('click', function(){
                const parent = $(this).closest('.cart-item');
                const qtyInput = parent.find('.qty');
                const quantity = parseInt(qtyInput.val());
                if (quantity > 1) {
                    qtyInput.val(quantity - 1);
                    updateSubtotal(parent);
                    updateChangeDisplay();  
                }
            });

            $('.remove-btn').off('click').on('click', function(){
                const parent = $(this).closest('.cart-item');
                const id = parent.data('id');
                cartItems = cartItems.filter(item => item.id !== id);
                renderCart();
                updateTotalDisplay();
                updateChangeDisplay();
            });
        
        }


        // CONFIRMATION OF PAYMENT 

        $('#process-button').on('click', function(){

            const paymentMethod = $('#paymentMethod').val();
            const amountInput = $('.amount-input').val();
            const referenceInput =  $('.reference-input').val();
            
            const selectedPaymentText = $('#paymentMethod option:selected').text();
            const amount = $('.amount-value').text();
            const change = $('.change-value').text();
            const total = $('.total-value').text();

            // NUMERIC VALUES

            amountNumericValue = parseInt(amount.replace(/,/g, '').split('.')[0], 10);
            totalNumericValue = parseInt(total.replace(/,/g, '').split('.')[0], 10);
            changeNumericValue = parseInt(change.replace(/,/g, '').split('.')[0], 10);


            if (cartItems.length === 0){    
                Swal.fire({
                    icon: 'error',
                    title: 'Cart should not be empty',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'my-swal-btn'
                    },
                });
            }
            else if(paymentMethod == null || paymentMethod === 0){
                Swal.fire({
                    icon: 'error',
                    title: 'Select Payment Method',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'my-swal-btn'
                    },
                });
            }
            else{

                if (paymentMethod == 1){

                    if(amountInput == null || amountInput == 0){
                        Swal.fire({
                            icon: 'error',
                            title: 'Please Enter Amount',
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'my-swal-btn'
                            },
                        });
                        return;
                    }

                    if (totalNumericValue > amountNumericValue){
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Amount Entered',
                            text: 'The amount should be equal to or greater than the total amount.',
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'my-swal-btn'
                            },
                        });
                        return;
                    }

                }
                else{
                    if(referenceInput == null || referenceInput == ''){
                        Swal.fire({
                            icon: 'error',
                            title: 'Please Enter Reference Number',
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'my-swal-btn'
                            },
                        });
                        return;
                    }
                }

                let rows = '';
                cartItems.forEach(item => {
                    rows += `
                        <tr>
                            <td style="padding: 6px; border: 1px solid #999; text-align: center; font-size: 13px;">${item.name}</td>
                            <td style="padding: 6px; border: 1px solid #999; text-align: center; font-size: 13px;">${item.code}</td>
                            <td style="padding: 6px; border: 1px solid #999; text-align: center; font-size: 13px;">${item.quantity}</td>
                            <td style="padding: 6px; border: 1px solid #999; text-align: center; font-size: 13px;">${item.subtotal}</td>
                        </tr>
                    `;
                });

                const tableHtml = `
                    <div>
                        <table class="responsive-table" style="font-size: 13px;">
                            <thead>
                                <tr style="background-color: #f2f2f2;">
                                    <th style="padding: 8px; border: 1px solid #999;">Item Description</th>
                                    <th style="padding: 8px; border: 1px solid #999;">JAN #</th>
                                    <th style="padding: 8px; border: 1px solid #999;">Qty</th>
                                    <th style="padding: 8px; border: 1px solid #999;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${rows}
                            </tbody>
                        </table>

                         <table class="summary-table">
                            <tr>
                                <td class="summary-label">Mode of Payment</td>
                                <td style="text-align: center;">${selectedPaymentText}</td>
                            </tr>
                            ${paymentMethod != 1 ? `
                                <tr>
                                    <td class="summary-label">Reference Number</td>
                                    <td style="text-align: center;">${referenceInput}</td>
                                </tr>
                            ` : ''
                            }
                            <tr>
                                <td class="summary-label">Total</td>
                                <td style="text-align: center;">₱${total}</td>
                            </tr>
                            ${paymentMethod == 1 ? `
                             <tr>
                                <td class="summary-label">Amount Received</td>
                                <td style="text-align: center;">₱${amount}</td>
                            </tr>
                            <tr>
                                <td class="summary-label">Change</td>
                                <td style="text-align: center;">₱${change}</td>
                            </tr>` : ''
                            
                            }
                           
                        </table>
                    </div>
                `;

                Swal.fire({
                    title: 'Transaction Details',
                    html: tableHtml,
                    confirmButtonText: 'Confirm',
                    cancelButtonText: 'Cancel',
                    width: '100%',
                    showCancelButton: true,
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'my-swal-btn',
                        popup: 'custom-swal'
                    },
                }).then((result) => {

                    showSpinner();

                    if (isSubmitting) return;
                    isSubmitting = true;

                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('submit.item.transaction') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                items: cartItems,
                                mode_of_payment: paymentMethod,
                                mode_of_payment_text: selectedPaymentText,
                                total: totalNumericValue,
                                amount_entered: amountNumericValue,
                                change: changeNumericValue,
                                reference_number: referenceInput,
                            },
                            success: function (response) {

                                if (response.status == 'success'){
                                    const successHtml = `
                                        <div>
                                            <table class="summary-table">
                                                <tr>
                                                    <td class="summary-label">Reference Number</td>
                                                    <td style="text-align: center;">${response.reference_number}</td>
                                                </tr>
                                                <tr>
                                                    <td class="summary-label">Mode of Payment</td>
                                                    <td style="text-align: center;">${response.mode_of_payment}</td>
                                                </tr>
                                                ${paymentMethod != 1 ? `
                                                    <tr>
                                                        <td class="summary-label">Payment Reference</td>
                                                        <td style="text-align: center;">${response.payment_reference}</td>
                                                    </tr>
                                                ` : ''
                                                }
                                                <tr>
                                                    <td class="summary-label">Total</td>
                                                    <td style="text-align: center;">₱${response.total}</td>
                                                </tr>
                                                ${paymentMethod == 1 ? `
                                                <tr>
                                                    <td class="summary-label">Amount Received</td>
                                                    <td style="text-align: center;">₱${response.amount_entered}</td>
                                                </tr>
                                                <tr>
                                                    <td class="summary-label">Change</td>
                                                    <td style="text-align: center;">₱${response.change}</td>
                                                </tr>` : ''
                                                
                                                }
                                            
                                            </table>
                                        </div>
                                    `;

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Transaction Success',
                                        html: successHtml,
                                        customClass: {
                                            confirmButton: 'my-swal-btn'
                                        },
                                    }).then((result)=>{
                                        if (result.isConfirmed){
                                            window.location.href = "{{ route('item_pos') }}";
                                        }
                                    });
                                }
                                else{
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Transaction Failed',
                                        text: 'There was an error please try again.',
                                        customClass: {
                                            confirmButton: 'my-swal-btn'
                                        },
                                    });
                                }

                               
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Transaction Failed',
                                    text: 'There was an error please try again.',
                                    customClass: {
                                        confirmButton: 'my-swal-btn'
                                    },
                                });
                            },
                            complete: function () {
                                hideSpinner();
                                isSubmitting = false;
                            }
                        });
                    }
                });

    

            }

        });

    });

</script>
@endsection