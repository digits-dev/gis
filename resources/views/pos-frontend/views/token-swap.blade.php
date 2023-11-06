{{-- Please always check the current plugins, css, script in content.blade.php--}}

{{-- Extend the dashboard layout --}}
@extends('pos-frontend.components.content')

{{-- Your Plugins --}}
@section('plugins')
@endsection

{{-- Your CSS --}}
@section('css')
<style>
  .main-container {
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: "Open Sans", sans-serif;
  }
  .container {
    width: 460px;
    background-color: white;
    box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px,
      rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
    border-radius: 30px;
    padding: 20px 30px;
    display: none;
  }
  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .btn-reverse {
    margin-top: 8px;
    display: flex;
    justify-content: center;
  }
  .btn-reverse-icon {
    cursor: pointer;
    border-radius: 10px;
    background-color: rgb(210, 206, 206);
    padding: 10px;
    transition: background-color 0.3s;
  }
  .btn-reverse-icon:hover {
    background-color: #e60213;
  }
  .float-wrapper label i {
    font-size: 20px;
  }
  .float-wrapper {
    margin: 8px;
 
  }
  .peso-span, .token-span, .mod-span {
    margin: 18px;
  }
  .container option {
    font-size: 14px;
  }
  .input-field {
    text-align: right;
    padding: 12px;
    outline: none;
    height: 45px;
    width: 100%;
    border-radius: 10px;
    border-style: solid;
    background-color: #ffbfbf;
    border-color: red;
    margin-bottom: 10px;
    font-size: 19px;
  }
  .mode-of-payment {
    display: flex;
    width: 100%;
    border: 2px solid #ffbfbf;
    height: 45px;
    align-items: center;
    border-radius: 10px;
    padding: 0 20px;
    margin: 16px 0;
  }
  .mode-of-payment select {
    width: 100%;
    background: none;
    cursor: pointer;
  }
  .acc-number {
    margin-top: 10px;
  }
  .summary {
    display: flex;
    justify-content: space-evenly;
    margin-bottom: 16px;
  }
  .summary-value {
    gap: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }
  .change-value, .total-value {
    display: flex;
    justify-content: center;
    text-align: center;
    padding: 6px 12px;
    width: 100px;
    height: 40px;
    border-radius: 10px;
    border: 2px solid #e60213;
    background-color: #ffbfbf;
  }
  .btn-swap {
    cursor: pointer;
    width: 100%;
    height: 40px;
    font-size: 20px;
    font-weight: bold;
    background-color: #e60213;
    border: none;
    color: white;
    border-radius: 10px;
    transition: background-color 0.3s;
  }
  .btn-swap:hover {
    background-color: #a43f3f;
  }

  .summary-value span{
    font-size: 19px;
  }
  .styled-table-swap {
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

.styled-table-swap td {
  border: 1px solid #838383;
  padding: 12px 15px;
}

.styled-table-swap tr td:nth-of-type(odd) {
      color: #c02f2f;
}
.swal2-confirm {
  width: 95px;
}
</style>
@endsection

{{-- Define the content to be included in the 'content' section --}}
@section('content')
<div class="main-container">
    <div class="container">
        <div class="header">
            <h1 style="font-size: 19px;">Swap</h1>
            <p>{{ $cash_value }} per token</p>
        </div>
        @if (session('message'))
        <div x-data="{show: true}" x-init="setTimeout(()=> show = false, 3000)" x-show='show' class="fixed top-0 left-1/2 trasnform -translate-x-1/2 bg-laravel text-white px-24 py-3">
          <p>
              {{ session('message') }}
          </p></div>
    @endif
        <form method="POST" autocomplete="off">
          @csrf
            <div class="float-wrapper">
                  <i class="fa-solid fa-peso-sign peso-sign"></i>
                  <span class="peso-span">Peso</span>
            </div>
            <div class="amount" id="div1">
                <input class="input-field" type="text" name="cash_value" id="cash_value" oninput="onInput1(this)" />
            </div>
            <div class="btn-reverse" id="btn-reverse">
                <i
                class="fa-solid fa-right-left btn-reverse-icon"
                onclick="swap()">
                </i>
            </div>
            <div class="float-wrapper" id="float-wrapper2">
                    <i class="fa-solid fa-coins token"></i>
                    <span class="token-span">Token</span>
            </div>
            <div class="from" id="div2">
                <input class="input-field token-value" type="text" name="token_value" id="token_value" oninput="onInput2(this)" readonly />
            </div>
            <div class="float-wrapper">
              <i class="fas fa-money-check"></i>
              <Span class="mod-span">Mode of Payment</Span>
            </div>
            <div class="mode-of-payment">
                <select name="mode_of_payment" id="mode_of_payment">
                    <option value="" disabled selected>Mode of Payment</option>
                    @foreach ($mode_of_payments as $mode_of_payment )
                    <option value="{{ $mode_of_payment->id  }}">{{ $mode_of_payment->payment_description  }}</option>                      
                    @endforeach
                </select>
            </div>
            <div id="mode_of_payment_description"></div>
            <div id="payment_reference_div">
              <span id="reference" >Reference Number</span>
              <input class="input-field acc-number" type="text" name="amount_received" id="amount_received" oninput="onAmountReceived()">
              <input class="input-field acc-number" type="text" name="payment_reference" id="payment_reference">
            </div>
            <div class="summary">
                <div class="summary-value">
                    <span>Total</span>
                    <input class="total-value" type="text" name="total_value" id="total_value" readonly placeholder="0">
                </div>
                  <div class="summary-value">
                    <span>Change</span>
                    <input class="change-value" type="text" name="change_value" id="change_value" readonly placeholder="0">
                </div>
            </div>
            <button class="btn-swap" type="submit" >Swap</button>
        </form>
    </div> 
</div>
@endsection

{{-- Your Script --}}

@section('script-js')
<script src="{{ asset('plugins/sweetalert.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

      $(document).ready(function() {
          $(".container").fadeIn(1000);
          $('#mode_of_payment').attr('disabled', true);
      });
      
    const float1Input = document.getElementById("cash_value");
    const float2Input = document.getElementById("token_value");
    const amountReceivedInput = document.getElementById("amount_received");
    const changeElement = document.getElementById("change_value");
    const totalElement = document.getElementById("total_value");
    const mod = document.getElementById('mode_of_payment');

    $(document).on('keyup','#cash_value,#token_value', function (e) {
        if(event.which >= 37 && event.which <= 40) return;

        if(this.value.charAt(0) == '.'){
            this.value = this.value.replace(/\.(.*?)(\.+)/, function(match, g1, g2){
                return '.' + g1;
            })
        }

        // if(event.key == '.' && this.value.split('.').length > 2){
        if(this.value.split('.').length > 2){
            this.value = this.value.replace(/([\d,]+)([\.]+.+)/, '$1') 
                + '.' + this.value.replace(/([\d,]+)([\.]+.+)/, '$2').replace(/\./g,'')
            return;
        }

        $(this).val( function(index, value) {
            value = value.replace(/[^0-9.]+/g,'')
            let parts = value.toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join(".");
        });

        if(event.which >= 37 && event.which <= 40) return;
        $(this).val(function(index, value) {
            return value
            .replace(/\D/g, "")
            .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            ;
     });
});


    function onInput1(float1Input) {
      float1Input.value = Number(float1Input.value.replace(/[^0-9]/g,''));
      const float1Value = float1Input.value;
      const converted = Math.floor(float1Value / {{ $cash_value }});
      const remainder = float1Value.replace(/,/g, '') % {{ $cash_value }};
      const total = converted * {{ $cash_value }};
      const amountReceivedTotal = total + remainder;
      $('#mode_of_payment').attr('disabled', false);
      if (float1Value) {
        float2Input.value = converted.toLocaleString();
        totalElement.value = total.toLocaleString();
        amountReceivedInput.value = amountReceivedTotal.toLocaleString();

            if(mod.value == 1 || float2Input.readOnly == false){
              changeElement.value = remainder;
              // amountReceivedInput.readOnly = false;
            }
            // else {
            //   amountReceivedInput.readOnly = true;
            // }
      } else {
        float2Input.value = "";
        changeElement.value = "0";
        totalElement.value = "0";
      }
    }

    function onInput2(float2Input) {
      float2Input.value = Number(float2Input.value.replace(/[^0-9]/g,''));
      const float2Value = float2Input.value;
      const converted = float2Value * {{ $cash_value }} ;
      $('#mode_of_payment').attr('disabled', false);
      if (float2Value) {
        float1Input.value = converted.toLocaleString();
        totalElement.value = converted.toLocaleString();
      } else {
        float1Input.value = "";
        totalElement.value = "0";
        changeElement.value = "0";
      }
    }
    
      function onAmountReceived() {
            float2Input.value = Number(float2Input.value.replace(/[^0-9]/g,''));
            const amountReceived = Number(amountReceivedInput.value.replace(/[^0-9]/g,''))
            amountReceivedInput.value =  amountReceived.toLocaleString();
            const float2Value = float2Input.value
            const totalAmount = float2Value * {{ $cash_value }} ;
            const changeToAmountRecieved = amountReceived - totalAmount;
            changeElement.value =  changeToAmountRecieved.toLocaleString();

          }

    $('#payment_reference_div').hide();
    $(document).ready(function() {
    $("#mode_of_payment").on("change", function() {
      float1Input.value = Number(float1Input.value.replace(/[^0-9]/g,''));
      const float1Value = float1Input.value;
      const converted = Math.floor(float1Value / {{ $cash_value }});
      const remainder = float1Value.replace(/,/g, '') % {{ $cash_value }};
      const selectedValue = $(this).val();
        
      const selectedDescription = $('option:selected', this).text();
      $('#mode_of_payment_description').text(selectedDescription);
      $('#mode_of_payment_description').hide();

        if(selectedValue != 1){
          $('#change_value').val('0');
          $('#payment_reference').val("");
          $('#reference').text("Reference Number"); 
          $('#payment_reference').fadeIn(); 
          $('#amount_received').hide(); 
          $('#amount_received').val(""); 
          $('#payment_reference_div').fadeIn(1000);
        }else {
          $('#change_value').val(remainder);
          $('#amount_received').val(float1Value);
          $('#payment_reference_div').fadeIn(1000);
          $('#reference').text("Amount Received"); 
          $('#amount_received').fadeIn(); 
          $('#amount_received').val(""); 
          $('#payment_reference').hide();  
        }
       
    });
});

    let isSwapped = false;

    function swap() {
   
      const pesoSignIcon = document.querySelector(".peso-sign");
      const tokenIcon = document.querySelector(".token");
      const tokenSpan = document.querySelector(".token-span");
      const pesoSpan = document.querySelector(".peso-span");

      
      const change = document.getElementById('change_value')
      const div1 = document.getElementById("div1");
      const div2 = document.getElementById("div2");
      const floatWrapper2 = document.getElementById("float-wrapper2");
      const btnReverse = document.getElementById("btn-reverse");
      const parent = div1.parentNode;

      change.value = ""
      // swap icons
      const tempIconClass = pesoSignIcon.className;
      pesoSignIcon.className = tokenIcon.className;
      tokenIcon.className = tempIconClass;

      // swap text
      const tempText = pesoSpan.textContent;
      pesoSpan.textContent = tokenSpan.textContent;
      tokenSpan.textContent = tempText;

      // Swap readonly
      if (float1Input.readOnly) {
            float1Input.readOnly = false;
            float2Input.readOnly = true;
            // amountReceivedInput.readOnly = true;
        } else {
            float1Input.readOnly = true;
            float2Input.readOnly = false;
            // amountReceivedInput.readOnly = false;
        }

      // swap div position
      if (isSwapped) {
        parent.insertBefore(div1, div2);
        parent.insertBefore(floatWrapper2, div2);
        parent.insertBefore(btnReverse, floatWrapper2);
        isSwapped = false;
      } else {
        parent.insertBefore(div2, div1);
        parent.insertBefore(floatWrapper2, div1);
        parent.insertBefore(btnReverse, floatWrapper2);
        isSwapped = true;
      }

      float1Input.value = Number(float1Input.value.replace(/[^0-9]/g,''));
      const float1Value = float1Input.value;
      const converted = Math.floor(float1Value / {{ $cash_value }});
      const total = converted * {{ $cash_value }};
  
      float1Input.value = total.toLocaleString();
      // amountReceivedInput.value = totalElement.value;
      amountReceivedInput.value = "";
    }
    
    $(document).ready(function() {
    $('form').submit(function(e) {
        e.preventDefault(); // Prevent the form from submitting the traditional way.
        const formData = $('form').serialize();
        const cashValue = Number($('#cash_value').val().replace(/[.,]/g, ''));
        const amountReceived = Number($('#amount_received').val().replace(/[.,]/g, ''));
        if($('#cash_value').val() === '' && $('#token_value').val() === ''){
                Swal.fire({
                    type: 'error',
                    title:'Cash or Token Required!',
                    icon: 'error',
                    confirmButtonColor: '#367fa9',
                });
            }
          else if(cashValue < {{ $cash_value }}  ){
                Swal.fire({
                        type: 'error',
                        title: 'Value is not enough for 1 Token!',
                        icon: 'error',
                        confirmButtonColor: '#367fa9',
                    });
            }
          else if(cashValue > amountReceived && $('#mode_of_payment').val() == 1){
                Swal.fire({
                        type: 'error',
                        title: 'Amount Received should be greater than Peso Amount!',
                        icon: 'error',
                        confirmButtonColor: '#367fa9',
                    });
            }
          else if(!$('#mode_of_payment').val()){
                Swal.fire({
                        type: 'error',
                        title: 'Please choose mode of payment!',
                        icon: 'error',
                        confirmButtonColor: '#367fa9',
                    });
            }
          else if( $('#payment_reference').val() === '' && $('#mode_of_payment').val() != 1) {
            Swal.fire({
                        type: 'error',
                        title: 'Reference Number is Required!',
                        icon: 'error',
                        confirmButtonColor: '#367fa9',
                    });
          }else if (Number("{{ $inventory_qty }}") <  Number($('#token_value').val().split(",").join(""))) {
            Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Token Quantity exceeds the available quantity in the Token Inventory!'
                  })
                }
        else {
          Swal.fire({
                  title: 'Are you sure you want to continue?',
                  icon: 'info',
                  html: '<table class="styled-table-swap">' +
                          '<tr><td>Number of Tokens</td><td>'+ $('#token_value').val().replace(/\B(?=(\d{3})+(?!\d))/g,",")+'</td></tr>' +
                          '<tr><td>Mode of Payment</td><td>' + $('#mode_of_payment_description').text() + '</td></tr>' +
                          ($('#mode_of_payment').val() == 1 ? '<tr><td>Amount Received</td><td>' + $('#amount_received').val().replace(/\B(?=(\d{3})+(?!\d))/g,",") + '</td></tr>' : '')  +
                          '<tr><td>Total</td><td>'+ $('#total_value').val().replace(/\B(?=(\d{3})+(?!\d))/g,",")+'</td></tr>' +
                          '</table>',
                  showCancelButton: true,
                  reverseButtons: true,
                  confirmButtonText: 'Yes',
                }).then((result) => {
                  if (result.isConfirmed) {
                    $.ajax({
                    type: 'POST',
                    url: "{{ route('swap') }}",
                    data: formData,
                    success: function(res) {
                        const data = JSON.parse(res);
                        console.log(data)
                    if (data.message === 'success') {
                  Swal.fire({
                    icon: 'success',
                    title: 'Swap Successfully!',
                    allowOutsideClick: false,
                    html: '<table class="styled-table-swap">' +
                          '<tr><td>Reference Number</td><td>'+ data.reference_number +'</td></tr>' +
                          '<tr><td>Number of Tokens</td><td>'+ $('#token_value').val().replace(/\B(?=(\d{3})+(?!\d))/g,",")+'</td></tr>' +
                          '<tr><td>Mode of Payment</td><td>' + $('#mode_of_payment_description').text() + '</td></tr>' +
                          ($('#mode_of_payment').val() == 1 ? '<tr><td>Amount Received</td><td>' + $('#amount_received').val().replace(/\B(?=(\d{3})+(?!\d))/g,",") + '</td></tr>' : '')  +
                          '<tr><td>Total</td><td>'+ $('#total_value').val().replace(/\B(?=(\d{3})+(?!\d))/g,",")+'</td></tr>' +
                          '</table>',
                  }).then((result) => {
                          history.go(0);
                        })
                } 

            },
            error: function(err) {
                console.log(err);
            }
        });
                  }
              })
        }
    });
});
  </script>
@endsection