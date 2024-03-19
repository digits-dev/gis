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
    position: relative;
  }
  .container {
    width: 460px;
    background-color: white;
    box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px,
      rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
    border-radius: 30px;
    padding: 20px 30px;
    display: none;
    z-index: 10;
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
  .select2-container--default .select2-selection--single {
    width: 100% !important;
    display: flex !important;
    background: none !important;
    cursor: pointer !important;
    border: 2px solid #ffbfbf !important;
    height: 45px !important;
    align-items: center !important;
    border-radius: 10px !important;
    padding: 0 20px !important;
}
.select2-selection__arrow{
  margin-top: 8px;
  margin-right: 18px;

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

  .summary-value span{
    font-size: 19px;
  }
  .styled-table-swap {
    font-size: 0.9em;
    font-family: sans-serif;
    width: 100%;
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

.addons-table, .jan-desc-table{
  border: 1px solid #ffbfbf;
  border-radius: 13px; 
  border-spacing: 0;
  width: 100%;
  margin: 12px auto;
  margin-bottom: 20px;
  font-weight: normal;
  overflow: hidden;
}
.addons-table th , .addons-table td, .jan-desc-table th , .jan-desc-table td{
  text-align: left;
  text-align: center;
  padding: 5px 10px; 
  border: 1px solid #ffbfbf;
}
.addons-table td, .jan-desc-table td{
font-size: 14px;
}

.addons-table th:nth-child(1), .jan-desc-table th:nth-child(1){
  border-radius: 10px 0 0 0;
}
.addons-table th:nth-child(3), .jan-desc-table th:nth-child(3){
  border-radius: 0 10px 0 0;
}

.addons-table th, .jan-desc-table th{
  font-weight: normal;
}

.addons-table td input, .jan-desc-table td input  {
  text-align: center;
}

.swal2-confirm {
  width: 95px;
}
.presets, .paymaya {
  align-self: flex-start;
  margin-top: 50px;
  background-color: #f0f0f0;
  width: 220px;
  height: 100%;
  display: flex;
  flex-wrap: wrap;
  background-color: white;
  border-radius: 0 10px 10px 0px;
  z-index: 5;
  transition: width 0.5s, height 0.5s;
  box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px,
      rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
  position: relative;
}

.paymaya{
  padding: 10px;
  border-radius: 0 10px 10px 10px;
  margin-top: 0;
  z-index: 4;
}
.presets-header {
  display: flex;
  gap: 20px;
}
.preset-label{
  align-self: center;
  font-weight: bold;
}
.presets-value {
  padding: 10px;
  background-color: #e60213;
  border-radius: 10px;
  border: none;
  width: 50px;
  cursor: pointer;
  color: white;
}
.presets-value:hover {
  background-color: #c02f2f;
}
.btn-preset {
  align-self: flex-start;
}
.bar-icon{
 font-size: 20px;
 padding: 12px 14px;
 cursor: pointer;
 background-color: white;
 transition: background-color 0.3s;
 border-radius: 0 10px 10px 0px;
}
.bar-icon:hover {
  background-color: #e60213;
}
.presets-value-div {
  display: grid;
  grid-template-columns: auto auto auto;
  gap: 20px;
  margin: 16px;
}
.paymaya-div {
  display: flex;
  gap: 20px;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  margin: 20px;
}
.horizontal-line {
 	width: 100%;
	border-bottom: 1px solid rgb(189, 189, 189);
}
.addons-container {
  margin: 8px;
  display: flex;
  gap: 10px;
  align-items: center;
  text-align: center;
}
.addons {
    display: flex;
    width: 100%;
    border: 2px solid #ffbfbf;
    height: 45px;
    align-items: center;
    border-radius: 10px;
    padding: 0 20px;
    margin: 16px 0;
  }
  .addons-selection {
    width: 100%;
  }
/* CSS */
.button-pushable {
  position: relative;
  border: none;
  background: transparent;
  padding: 0;
  cursor: pointer;
  outline-offset: 4px;
  transition: filter 250ms;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
}

.button-shadow {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: 12px;
  background: hsl(0deg 0% 0% / 0.25);
  will-change: transform;
  transform: translateY(2px);
  transition:
    transform
    600ms
    cubic-bezier(.3, .7, .4, 1);
}

.button-edge {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: 12px;
  background: linear-gradient(
    to left,
    hsl(340deg 100% 16%) 0%,
    hsl(340deg 100% 32%) 8%,
    hsl(340deg 100% 32%) 92%,
    hsl(340deg 100% 16%) 100%
  );
}

.button-front {
  padding: 10px;
  border: none;
  width: 50px;
  cursor: pointer;
  display: block;
  position: relative;
  border-radius: 12px;
  font-size: 1.1rem;
  color: white;
  background-color: #e60213;
  will-change: transform;
  transform: translateY(-4px);
  transition:
    transform
    600ms
    cubic-bezier(.3, .7, .4, 1);
}

@media (min-width: 768px) {
  .button-front {
    width: 50px;
    height: 50px;
    font-size: 1.25rem;
  }
}

.button-pushable:hover {
  filter: brightness(110%);
  -webkit-filter: brightness(110%);
}

.button-pushable:hover .button-front {
  transform: translateY(-6px);
  transition:
    transform
    250ms
    cubic-bezier(.3, .7, .4, 1.5);
}

.button-pushable:active .button-front {
  transform: translateY(-2px);
  transition: transform 34ms;
}

.button-pushable:hover .button-shadow {
  transform: translateY(4px);
  transition:
    transform
    250ms
    cubic-bezier(.3, .7, .4, 1.5);
}

.button-pushable:active .button-shadow {
  transform: translateY(1px);
  transition: transform 34ms;
}

.button-pushable:focus:not(:focus-visible) {
  outline: none;
}

 .btn-swap {
    cursor: pointer;
    width: 100%;
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
  }
  .btn-paymaya {
    cursor: pointer;
    width: 100%;
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 15px;
  }
.btn-reset-div {
  margin: 16px 10px;
}
.btn-reset {
  width: 200px;
}
.presets-container {
 align-self: flex-start;
 display: none;
}

.swal-button--confirm{
 background-color: #7066e0;
}

.swal-footer {
  text-align: center; 
}

.parent{
  position: relative;
}
.child{
  position: absolute;
}
.lessQty,
.janLessQty {
  color: #e60213;
  cursor: pointer;
}
.lessQty:hover {
  filter: brightness(110%);
  -webkit-filter: brightness(110%);
}
.swal-table-container {
  display: flex;
  gap: 20px;
}
.swal-container {
  width: 70%;
}
.swal-container2 {
  width: 70%;
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
              <div class="jan-number-div">
                <select name="jan_number" id="jan_number" >
                    <option value="" disabled selected>Input JAN Number</option>
                </select>
                <div>
                  <div class="jan-description-table-wrapper">
                    <table class="jan-desc-table">
                      <thead>
                        <tr>
                          <th>
                            Description
                          </th>
                          <th>Quantity</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody id="jan-desc-tbody">
                        <!-- Add rows dynamically here -->
                    </tbody>
                    </table>
                  </div>
                  {{-- <span>Item Description:</span>
                  <input class="input-field jan-item" type="text" readonly /> --}}
                </div>
              </div>
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
            <div class="addons-container">
              <Span>Addons</Span>
              <input type="checkbox" name="" id="myCheckbox">
              <div class="addons">
                <select class="addons-selection parent" name="addons" id="addons">
                    <option class="child" value="" disabled selected>Select Addons</option>
                    @foreach ($addons as $addon )
                    <option class="child" data-id="{{ $addon->digits_code ." - ". $addon->description  }}" value="{{ $addon->digits_code  }}">{{ $addon->digits_code ." - ". $addon->description  }} ({{ $addon->qty }})</option>                      
                    @endforeach
                </select>
              </div>
            </div>
            <div class="addon-table-wrapper" style="display: none;">
              <table class="addons-table">
                <thead>
                  <tr>
                    <th>
                      Description
                    </th>
                    <th>Quantity</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="addons-body">
                  <!-- Add rows dynamically here -->
              </tbody>
              </table>
            </div>
            {{-- <button class="btn-swap" type="submit" >Swap</button> --}}
              <button class="button-pushable btn-swap" role="button">
          <span class="button-shadow"></span>
          <span class="button-edge btn-swap-edge"></span>
          <span class="button-front text btn-swap">
            <i style="margin-right: 10px;" class="fa-solid fa-rotate"></i>
             Swap
          </span>
      </button>
   
        </form>
    </div> 
    <div class="presets-container">
      <div class="presets">
        <div class="presets-header">
          <div class="btn-preset">
            <i class="fa-solid fa-bars bar-icon" id="resizeButton"></i>
          </div>
          <span class="preset-label">Add Tokens</span>
        </div>
        <div class="horizontal-line"></div>
        <div class="presets-value-div">
          @foreach ($tokens as $token)
            <button class="btn button-pushable" role="button">
              <span class="button-shadow"></span>
              <span class="button-edge"></span>
              <span class="button-front text">
                  {{ $token->value }}
              </span>
          </button>
          @endforeach
        </div>
        <div class="horizontal-line"></div>
        <div class="btn-reset-div">
          <button class="button-pushable" role="button">
            <span class="button-shadow"></span>
            <span class="button-edge"></span>
            <span class="button-front text btn-reset">
              <i class="fas fa-undo"></i>
              Reset
            </span>
        </button>
        </div>
      </div>
    </div>

</div>
@endsection

{{-- Your Script --}}

@section('script-js')
<script src="{{ asset('plugins/sweetalert.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
      $('#jan_number').select2({
          width:'100%',
          theme: 'default', // Ensures the default Select2 styles are applied
      });

      $(document).ready(function() {
          $(".container").fadeIn(1000);
          $(".presets-container").fadeIn(1000);
          $('#mode_of_payment').attr('disabled', true);
          $("#cash_value").focus();
          $('#payment_reference_div').hide();
          $('.addons').hide();
      });

    const float1Input = document.getElementById("cash_value");
    const float2Input = document.getElementById("token_value");
    const amountReceivedInput = document.getElementById("amount_received");
    const changeElement = document.getElementById("change_value");
    const totalElement = document.getElementById("total_value");
    const mod = document.getElementById('mode_of_payment');

    // Presets
    const presetsDiv = document.querySelector('.presets');
    const resizeButton = document.getElementById('resizeButton');
    const presetsValueDiv = document.querySelector('.presets-value-div');
    const presetsLabel = document.querySelector('.preset-label');
    const horizontalLine = document.querySelector('.horizontal-line');
    const resetDiv = document.querySelector('.btn-reset-div');

    // Jan Description
    let jan_data = [];


    resizeButton.addEventListener('click', () => {
      if (presetsDiv.offsetWidth === 44) {
        presetsDiv.style.width = '220px';
        presetsDiv.style.height = '100%'; 
        presetsValueDiv.style.display = 'grid';
        presetsLabel.style.display = 'inline';
        horizontalLine.style.display = 'inline';
        resetDiv.style.display = 'inline';
      } else {
        presetsDiv.style.width = '44px';
        presetsDiv.style.height = '44px';
        presetsValueDiv.style.display = 'none';
        presetsLabel.style.display = 'none';
        horizontalLine.style.display = 'none';
        resetDiv.style.display = 'none';
      }
    });

    function removeCommas(localeString) {
            return localeString.replace(/,/g, '');
        }
      function setPresetValue(presetValue) {
            var currentCashValue = parseInt(removeCommas($("#cash_value").val())) || 0;
            var currentTokenValue = parseInt(removeCommas($("#token_value").val())) || 0;
            var currentTotalValue = parseInt(removeCommas($("#total_value").val())) || 0;

            const tokenValue = presetValue * {{ $cash_value }};
            
            $("#cash_value").val((currentTotalValue + tokenValue).toLocaleString());
            $("#token_value").val((currentTokenValue + presetValue).toLocaleString());
            $("#total_value").val((currentTotalValue + tokenValue).toLocaleString());

        }
      $(document).ready(function() {
            $(".btn").click(function() {
                var presetValue = parseInt($(this).text());
                setPresetValue(presetValue);
                $('#mode_of_payment').attr('disabled', false);
                amountReceivedInput.value = "";
                $('#payment_reference').val("");
                changeElement.value = "0";
            });
        });

        $('.btn-paymaya').click(function () {
          const paymayaPresetValue = $(this).text().trim();
          $('#payment_reference').val(paymayaPresetValue + " ");
          $('#payment_reference').focus() 
        })

        $(document).ready(function() {
          $('.btn-reset').click(function() {
            amountReceivedInput.value = "";
            $("#cash_value").val("")
            $("#token_value").val("")
            $("#total_value").val("")
            $("#change_value").val("")
            $("#mode_of_payment").val("");
            $('#mode_of_payment').attr('disabled', true);
            $('#payment_reference_div').fadeOut();
            
            // ADDONS
            addonsObject = <?php echo json_encode($addons); ?>;
            $("#myCheckbox").prop("checked", false);
            $('.addons').fadeOut();
            $("#addons-body").empty();
            $('.addon-table-wrapper').fadeOut();
            reEnableOptions();
            reRenderQty();
            formatTable();


            if(float1Input.readOnly) {
              $("#token_value").focus();  
            }else {
              $("#cash_value").focus();
            }

          })
        });


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
        amountReceivedInput.value = "";
        changeElement.value = "0";
            // if(mod.value == 1 || float2Input.readOnly == false){
            //   changeElement.value = remainder;
            //   // amountReceivedInput.readOnly = false;
            // }
            // // else {
            // //   amountReceivedInput.readOnly = true;
            // // }
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
        amountReceivedInput.value = "";
        changeElement.value = "0";
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

    $("#mode_of_payment").on("change", function() {
      const float1Value = Number(float1Input.value.replace(/[^0-9]/g,''));
      const selectedValue = $(this).val();
      const selectedDescription = $('option:selected', this).text();
      $('#mode_of_payment_description').text(selectedDescription);
      $('#mode_of_payment_description').hide();


        if(selectedValue == 32){
          $('#payment_reference').hide();
          $('.jan-number-div').fadeIn(1000);
          $('#reference').text("JAN Number:"); 
          $('.addons-container').hide();
          $('#amount_received').hide(); 
          $('#payment_reference_div').fadeIn(1000);


        }
        else if(selectedValue != 1 ){
          $('#change_value').val('0');
          $('#payment_reference').val("");
          $('#reference').text("Reference Number"); 
          $('#payment_reference').fadeIn(1000); 
          $('#amount_received').hide(); 
          $('.jan-number-div').hide();  
          $('#amount_received').val(""); 
          $('#jan_number').val("").trigger('change'); // Set the value of #jan_number and trigger change event
          $('.jan-item').val(""); 
          $('#payment_reference_div').fadeIn(1000);
          $('#payment_reference').focus();  
          $("#jan-desc-tbody tr").remove();
          $('.addons-container').fadeIn(1000);

          jan_data = [];
        }

        
        else {
          $('#amount_received').val(float1Value);
          $('#payment_reference_div').fadeIn(1000);
          $('#reference').text("Amount Received"); 
          $('#amount_received').fadeIn(); 
          $('#amount_received').val(""); 
          $('#payment_reference').hide();  
          $('.jan-number-div').hide();  
          $('#amount_received').focus()
          $('#jan_number').val(""); 
          $('.jan-item').val("");
          $('.addons-container').fadeIn(1000);
          $("#jan-desc-tbody tr").remove();
          jan_data = [];


        }
          
        });

    let isSwapped = false;
    let cashValueHasFocus = true;

    $('#jan_number').select2({
        ajax: {
            url: "{{ route('suggest_jan_number') }}",
            dataType: 'json',
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        width: '100%'
    })
    .on('select2:select', function (e) {

      const data = e.params.data;
      $('.jan-item').val(data.description);
      const existingObjectIndex = jan_data.findIndex(obj => obj.jan_number === data.text);
      if (existingObjectIndex !== -1) {
          jan_data[existingObjectIndex].qty++;
          $(`#${data.text}`).text(jan_data[existingObjectIndex].qty);
          $(`input[inputjanqty="${data.text}"]`).val(jan_data[existingObjectIndex].qty);
          $(`input[no_of_tokens="${data.text}"]`).val(jan_data[existingObjectIndex].qty * data.no_of_tokens);
      } else {
          jan_data.push({
              "jan_number": data.text,
              'qty': 1,
              'no_of_tokens': data.no_of_tokens
          });


          const jan_obj = jan_data.find( (e) => e.jan_number === data.text )
          const htmlContent = `
              <tr data-row="">
                  <td>
                      <input type="hidden" name="jan_number[]" value="${data.text}">
                      <input type="hidden" inputjanqty="${data.text}" name="jan_qty[]" value="${jan_obj.qty}">
                      <input type="hidden" id='no_of_tokens' no_of_tokens="${data.text}" name="no_of_tokens[]" value="${jan_obj.no_of_tokens}">
                      <span>${data.text} - ${data.description} (${data.no_of_tokens})</span>
                  </td>
                  <td>
                      <span id="${data.text}">${jan_obj.qty}</span>
                  </td>
                  <td>
                      <i class="fas fa-minus-circle janLessQty" less-id="${data.text}" data-id=""></i>
                  </td>
              </tr>`;
          $("#jan-desc-tbody").append(htmlContent);


      }
      setTimeout( () => {
        $('#jan_number').val("").trigger('change');
      }, 500)
    });

    $(document).on('click', '.janLessQty', function() {
        const JanCode = $(this).attr('less-id');
        removeJanQuantity(JanCode); 
        // console.log(JanCode);
      });

    function removeJanQuantity(attrJanNumber)
    {
      const data = jan_data.find( (obj) => obj.jan_number === attrJanNumber)
        data.qty--
        $(`#${attrJanNumber}`).text(data.qty);
        $(`input[inputjanqty="${attrJanNumber}"]`).val(data.qty);

        if(data.qty === 0 ){
          $(`#${attrJanNumber}`).parents('tr').remove();
            jan_data = jan_data.filter( (obj) => obj.jan_number !== attrJanNumber );
        }
      // console.log(jan_data);
    }

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
        } else {
            float1Input.readOnly = true;
            float2Input.readOnly = false;
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

      // Swap focus
        if (cashValueHasFocus) {
          $("#token_value").focus();
          cashValueHasFocus = false;
        } else {
          $("#cash_value").focus();
          cashValueHasFocus = true;
        }

      float1Input.value = Number(float1Input.value.replace(/[^0-9]/g,''));
      const float1Value = float1Input.value;
      const converted = Math.floor(float1Value / {{ $cash_value }});
      const total = converted * {{ $cash_value }};
      
      if ($('#token_value').val() == "") {
        float1Input.value = "";
      }else {
        float1Input.value = total.toLocaleString();
      }
      amountReceivedInput.value = "";
    }

      var arrayDigitsCode = []
      var arrayDescription = [];

      var addonsObject = @json($addons);

      $("#addons").on("change", function () {
        let addonDigitsCode = $(this).val();
        let addonDescription = $("#addons option:selected").attr('data-id');
        if (!arrayDescription.includes(addonDescription)) {
          arrayDescription.push(addonDescription);
        }
        if (!arrayDigitsCode.includes(addonDigitsCode)) {
          arrayDigitsCode.push(addonDigitsCode);
        }

        let selectedDigitalCode = $(this).val();
        
        var selectedAddon = addonsObject.find(function (addon) {
          return addon.digits_code === selectedDigitalCode;
        });

        selectedAddon.qty -= 1;
        if (selectedAddon.qty == 0) {
          $(this).find('option[value="' + selectedDigitalCode + '"]').attr('disabled', true);
        }

        // var rowIdentifier = addonDescription.replace(/\s+/g, "-").toLowerCase(); // Create a unique identifier
        if (addonDigitsCode) {
            let existingRow = $("#addons-body tr[data-row='" + addonDigitsCode + "']");
            if (existingRow.length) {
                let quantityElement = existingRow.find("td:nth-last-child(2) input");
                let quantity = parseInt(quantityElement.val());
                quantityElement.val(quantity + 1);
            } else {
                $("#addons-body").append('<tr data-row="' + addonDigitsCode + '">' + 
                  '<td>'+
                    '<input type="hidden" name="digits_code[]" value="' + addonDigitsCode + '">' +
                    '<input type="hidden"  name="description[]" value="' + addonDescription + '">' +
                    '<span>'+addonDescription+'</span>' +
                    '</td>' +
                    '<td>' +
                     '<input style="width: 20%; font-size: 16px" type="text" name="quantity[]" id="qty_value'+addonDigitsCode+'" value="1" data-id="'+addonDigitsCode+'" readonly>' +
                    '</td>' +
                    '<td>' +
                      '<i class="fas fa-minus-circle lessQty" id="lessQty'+addonDigitsCode+'" data-id="'+addonDigitsCode+'"></i>' +
                    '</td>' +
                  '</tr>');
            }
            $('.addon-table-wrapper').fadeIn();
        }
          else {
              $("#addons-body").empty();
          } 
          setTimeout(() => {
            $(this).val('');
          }, 500);
          reRenderQty();
          formatTable();         
      });
        
      $(document).on('click', '.lessQty', function(e){
        const addonDigitsCode = $(this).attr('data-id');

        const currentValue = parseInt($('#qty_value'+addonDigitsCode).val());
        const newValue = currentValue - 1;

        const item = addonsObject.find(e => e.digits_code === addonDigitsCode);
        item.qty++;

        if (newValue == 0) {
          $(this).parents('tr').remove();
        }

        const tableWrapper = $(this).parents('.addon-table-wrapper');
        const selectedLength = $('.addon-table-wrapper #addons-body tr').get().length;
        if (!selectedLength) {
          $('.addon-table-wrapper').fadeOut();
        }
        
        $('#qty_value'+addonDigitsCode).val(newValue);
        reEnableOptions();
        reRenderQty();
        formatTable();

      });

    function reEnableOptions() {
      addonsObject.forEach(addon => {
        if (addon.qty) {
          $(`option[value="${addon.digits_code}"]`).attr('disabled', false);
        }
      });
    }

    function reRenderQty() {
      addonsObject.forEach(addon => {
        $(`option[value="${addon.digits_code}"]`).text(`${addon.digits_code} - ${addon.description} (${addon.qty})`);
      });
    }

    function formatTable() {
      const tbody = $('.addons-table tbody');
      const lastIndexTR = tbody.find('tr').get().length;
      tbody.find('tr').get().forEach((tr, index) => {
        $(tr).find('td').css('border-radius', '');

      });

      const thead = $('.addons-table thead');
      thead.find('tr').first().find('th:first-child').css({
        'width': '100%',
      });

      const lastTR = tbody.find('tr').eq(lastIndexTR - 1);
      const lastIndexTD = lastTR.find('td').get().length;
      lastTR.find('td').get().forEach((td, index) => {
        if (index === 0) {
          $(td).css('border-radius', '0 0 0 10px');
        } else if (index == lastIndexTD - 1) {
          $(td).css('border-radius', '0 0 10px 0');
        }
      })
    }
        
    $("#myCheckbox").click(function () {
      let isChecked = $(this).prop('checked');
      if(isChecked) {
        $('.addons').fadeIn();
        $('#addons').val('');
      }else {
        $('.addons').fadeOut();
        $("#addons-body").empty();
        addonsObject = <?php echo json_encode($addons); ?>;
        $('.addon-table-wrapper').fadeOut();
        reEnableOptions();
        reRenderQty();
        formatTable();

      }
    })



    $(document).ready(function() {
    $('form').submit(function(e) {
        e.preventDefault(); 
        const formData = $('form').serialize();
        
        const cashValue = Number($('#cash_value').val().replace(/[.,]/g, ''));
        const amountReceived = Number($('#amount_received').val().replace(/[.,]/g, ''));
   
        
        const addOns = [];
        const janNumber = [];
        
        $('.addons-table tbody tr').get().forEach(tr => {
          const row = $(tr);
          const obj = {};
          obj.description = row.find('input[name="description[]"]').val();
          obj.qty = row.find('input[name="quantity[]"]').val();

          addOns.push(obj);
        });

  

        $('.jan-desc-table tbody tr').get().forEach(tr => {
          const row = $(tr);
          const jan_obj = {};
          jan_obj.jan_number = row.find('input[name="jan_number[]"]').val();
          jan_obj.qty = row.find('input[name="jan_qty[]"]').val();
          jan_obj.no_of_tokens = row.find('input[name="no_of_tokens[]"]').val();
   
          janNumber.push(jan_obj);
        });

        let totalTokens = 0;
        janNumber.forEach(ojb => {
          totalTokens += parseInt(ojb.no_of_tokens);
        })


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
          else if($('#mode_of_payment').val() == 32 && !jan_data.length){
                Swal.fire({
                        type: 'error',
                        title: 'Please Input at least One Jan Number!',
                        icon: 'error',
                        confirmButtonColor: '#367fa9',
                    });
            }
          else if($('#mode_of_payment').val() == 32 && totalTokens != $('#token_value').val()){
                Swal.fire({
                        type: 'error',
                        title: 'Token should be equal to the total number of tokens of defective return!',
                        icon: 'error',
                        confirmButtonColor: '#367fa9',
                    });
            }
          else if( ($('#payment_reference').val() === '' && $('#mode_of_payment').val() != 1) && $('#mode_of_payment').val() != 32) {
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
          $('.btn-swap').css({"background-color": "gray", "border-radius": "12px"});
          $('.btn-swap').attr('disabled', true);
          swal({
            title: 'Are you sure you want to continue?',
            icon: 'info',
            closeOnClickOutside: false,
            className: ((janNumber.length != 0 || addOns.length != 0)? 'swal-container' : ''),
            content: {
            element: "div",
            attributes: {
              className: "swal-table-container",
              innerHTML: '<table class="styled-table-swap"><tr><td>Number of Tokens</td><td>'+ $('#token_value').val().replace(/\B(?=(\d{3})+(?!\d))/g,",")+'</td></tr>' +
              '<tr><td>Mode of Payment</td><td>' + $('#mode_of_payment_description').text() + '</td></tr>' +
              ($('#mode_of_payment').val() == 1 ? '<tr><td>Amount Received</td><td>' + $('#amount_received').val().replace(/\B(?=(\d{3})+(?!\d))/g,",") + '</td></tr>' : '')  +
              '<tr><td>Total</td><td>'+ $('#total_value').val().replace(/\B(?=(\d{3})+(?!\d))/g,",")+'</td></tr>' +
              '<tr style="background-color:#a5dc86;"><td>Change</td><td>'+ $('#change_value').val().replace(/\B(?=(\d{3})+(?!\d))/g,",")+'</td></tr>' +
              '</table>'+
              (addOns.length != 0 ? 
                  '<table class="styled-table-swap">'+
              '<tr><td colspan="2">Addons</td></tr>' +
              '<tr><td>Description</td><td>Quantity</td> </tr>' +
              addOns.map(item => `<tr><td>${item.description}</td><td>${item.qty}</td></tr>`).join('') +
                 '</table>' : '') +

                 (janNumber.length != 0 ? 
                  '<table class="styled-table-swap">'+
              '<tr><td colspan="2">Defective Return</td></tr>' +
              '<tr><td>Jan Number</td><td>Quantity</td> </tr>' +
              janNumber.map(item => `<tr><td>${item.jan_number}</td><td>${item.qty}</td></tr>`).join('') +
                 '</table>' : '')
                }
          },
            buttons: {
              cancel: {
                  text: "Cancel",
                  visible: true,
                  closeModal: true,
              },
              confirm: {
                  text: "Yes",
                  closeModal: false,
              },
        },
        }).then(save => {
          if (save) {
            // setTimeout(() => {
              return $.ajax({
                type: 'POST',
                url: "{{ route('swap') }}",
                data: formData,
                success: function(res) {
                    const data = JSON.parse(res);
                  swal.stopLoading();
                  swal.close();
                  if (data.message === 'success') {
                    Swal.fire({
                      icon: 'success',
                      title: 'Swap Successfully!',
                      customClass: ((janNumber.length != 0 || addOns.length != 0)? 'swal-container2' : ''),
                      allowOutsideClick: false,
                      html: 
                      '<div class="swal-table-container">'+
                        '<table class="styled-table-swap">' +
                            '<tr><td>Reference Number</td><td>'+ data.reference_number +'</td></tr>' +
                            '<tr><td>Number of Tokens</td><td>'+ $('#token_value').val().replace(/\B(?=(\d{3})+(?!\d))/g,",")+'</td></tr>' +
                            '<tr><td>Mode of Payment</td><td>' + $('#mode_of_payment_description').text() + '</td></tr>' +
                            ($('#mode_of_payment').val() == 1 ? '<tr><td>Amount Received</td><td>' + $('#amount_received').val().replace(/\B(?=(\d{3})+(?!\d))/g,",") + '</td></tr>' : '')  +
                            '<tr><td>Total</td><td>'+ $('#total_value').val().replace(/\B(?=(\d{3})+(?!\d))/g,",")+'</td></tr>' +
                            '<tr style="background-color:#a5dc86;"><td>Change</td><td>'+ $('#change_value').val().replace(/\B(?=(\d{3})+(?!\d))/g,",")+'</td></tr>' +
                        '</table>' +
                            (addOns.length != 0 ? 
                            '<table class="styled-table-swap">'+
                            '<tr><td colspan="2">Addons</td></tr>' +
                            '<tr><td style="width: 70%">Description</td><td>Quantity</td> </tr>' +
                            addOns.map(item => `<tr><td>${item.description}</td><td>${item.qty}</td></tr>`).join('') +
                        '</table>' : '') +
                            (janNumber.length != 0 ? 
                            '<table class="styled-table-swap">'+
                            '<tr><td colspan="2">Defective Return</td></tr>' +
                            '<tr><td>Jan Number</td><td>Quantity</td> </tr>' +
                            janNumber.map(item => `<tr><td>${item.jan_number}</td><td>${item.qty}</td></tr>`).join('') +
                            '</table>' : '') + 
                      '</div>'
                                
                    }).then((result) => {
                            history.go(0);
                    })
                } 
              },
              error: function(err) {
                  console.log(err);
              }
                });
            // }, 6000);
            
          } else {
            $('.btn-swap').css({"background-color": "#e60213", "border-radius": "12px"});
            $('.btn-swap').attr('disabled', false);
            }   
        
              })

              }
          });
      });
  </script>
@endsection