{{-- Please always check the current plugins, css, script in content.blade.php--}}

{{-- Extend the dashboard layout --}}
@extends('pos-frontend.components.content')
{{-- Title of the page --}}

{{-- Your Plugins --}}
@section('plugins')
    <link rel="stylesheet" href="{{ asset('css/item-pos.css') }}">
    <link rel="stylesheet" type="text/css" href="https://flatlogic.github.io/awesome-bootstrap-checkbox/demo/build.css" />
@endsection


{{-- Define the content to be included in the 'content' section --}}
@section('content')
    <div class="main-container">
        {{-- CONTAINER 1 --}}
        <div class="container-1">
            <form class="scanner-container">
                <div class="scanner-container-child1">
                    <p>Scan JAN Code</p>
                    <button class="active-scanner-button">
                        <img class="scanner-icon" src="{{ asset('img/item-pos/item-pos-scanner.png') }}" alt="">
                        <p>Activate Scanner</p>
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
                <div class="items-container" style="margin-top: 10px; font-size: 14px; gap:5px">
                    <div style="display: flex; justify-content: space-between">
                        <p style="font-weight: 600">Total</p>
                        <p><span>₱</span>1000.00</p>
                    </div>
                    <div style="display: flex; justify-content: space-between">
                        <p style="font-weight: 600">Amount</p>
                        <p><span>₱</span>1000.00</p>
                    </div>
                    <div style="border-top: 1px dashed black; margin: 4px 0;"></div>
                    <div style="display: flex; justify-content: space-between">
                        <p style="font-weight: 600">Change</p>
                        <p><span>₱</span>1000.00</p>
                    </div>
                </div>
                <div style="margin-top: 10px;">
                    <label class="payment-label">
                        <img src="{{ asset('img/item-pos/item-pos-payment.png') }}" alt="wallet" width="20">
                        Payment Method
                    </label>
                    <select class="payment-select" id="paymentMethod">
                        <option disabled selected>Choose Payment Method</option>
                        <option value="credit">Credit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                </div>
                <div style="margin-top: 10px;">
                    <label for="amount" class="amount-label">Amount</label>
                    <input type="text" id="amount" name="amount" class="amount-input" placeholder="Enter Amount">
                </div>    
                <button class="scan-button" style="margin-top: 12px">
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
@endsection

{{-- Your Script --}}

@section('script-js')
<script src="{{ asset('plugins/sweetalert.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script>
    $(document).ready(function() {

        $('#scan-btn').click(function(e) {
            e.preventDefault(); // prevent form submit

            var janCode = $('#jan-code-input').val();
            var ScannedQty = $('.quantity-number').text();

            if (janCode.trim() == '') {
                console.log('Please enter a JAN code.');
                return;
            }

            $.ajax({
                url: "{{ route('check.jan.code') }}", // you will define this route
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}", // Important for Laravel
                    jan_code: janCode,
                    scan_qty: ScannedQty,
                },
                success: function(response) {
                    addToCart(response.item);
                    console.log(response.item, response.data); // Here you will see the result
                },
                error: function(xhr) {
                    console.log('Error:', xhr.responseText);
                }
            });
        });


        $('.quantity-btn').click(function() {
            var $quantity = $(this).siblings('.quantity-number');
            var currentVal = parseInt($quantity.text());
            
            if ($(this).hasClass('plus')) {
                $quantity.text(currentVal + 1);
            } else if ($(this).hasClass('minus')) {
                if (currentVal > 1) { // Prevent going below 1
                    $quantity.text(currentVal - 1);
                }
            }
        });

        $('#paymentMethod').change(function() {
            const selected = $(this).val();
            console.log("Selected method:", selected);
        });

        renderCart();


    });

     // FOR ITEMS 
     let cartItems = [
            {id: 1, name: 'MOFUSAND LETS GET IN LINE2', code: '4582770000000', price: 449, quantity: 1 },
        ];

        function addToCart(newItem) {
            const existingItem = cartItems.find(item => item.code === newItem.code);
            if (existingItem) {
                // If item already exists, increase quantity
                existingItem.quantity += newItem.quantity;
               
            } else {
                // If new item, add it
                const maxId = cartItems.length > 0 ? Math.max(...cartItems.map(item => item.id)) : 0;
                newItem.id = maxId + 1;
                
                cartItems.push(newItem);
            }
            renderCart();
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
            } else {
                cartItems.forEach((item) => {
                    html += `
                        <div class="cart-item" data-id="${item.id}">
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
                                            <span class="subtotal-amount" style="font-weight: 600;" >${(item.price * item.quantity).toFixed(2)}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="border-top: 1px solid red; margin: 5px 0"></div>
                            <div style="display: flex; flex-direction: column; align-items: flex-end;">
                                <button class="remove-btn" style="display:flex; align-items:center; background: white; cursor:pointer;">
                                    <img style="width: 14px; height: auto" src="{{ asset('img/item-pos/item-pos-trash-can.png') }}" alt="">
                                    <span style="font-size: 12px; margin-left:5px;">Remove</span>
                                </button>
                            </div>
                        </div>
                    `;
                });
            }

            $('.item-container').html(html);
            $('#total-items').text(`${cartItems.length} items`);
            attachEvents();
        }

        function updateSubtotal(parent) {
            const id = parent.data('id');
            const item = cartItems.find(x => x.id === id);
            if (item) {
                const qty = parseInt(parent.find('.qty').val());
                item.quantity = qty;
                parent.find('.subtotal-amount').text((item.price * qty).toFixed(2));
            }
        }

        function attachEvents() {
            $('.qty-plus').off('click').on('click', function(){
                var parent = $(this).closest('.cart-item');
                var qtyInput = parent.find('.qty');
                var quantity = parseInt(qtyInput.val());
                qtyInput.val(quantity + 1);
                updateSubtotal(parent);
            });

            $('.qty-minus').off('click').on('click', function(){
                var parent = $(this).closest('.cart-item');
                var qtyInput = parent.find('.qty');
                var quantity = parseInt(qtyInput.val());
                if (quantity > 1) {
                    qtyInput.val(quantity - 1);
                    updateSubtotal(parent);
                }
            });

            $('.remove-btn').off('click').on('click', function(){
                var parent = $(this).closest('.cart-item');
                const id = parent.data('id');
                cartItems = cartItems.filter(item => item.id !== id);
                renderCart(); // Re-render cart
            });
        }

</script>
@endsection