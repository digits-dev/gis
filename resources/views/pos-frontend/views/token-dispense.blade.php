{{-- Please always check the current plugins, css, script in content.blade.php--}}

{{-- Extend the dashboard layout --}}
@extends('pos-frontend.components.content')
{{-- Title of the page --}}

{{-- Your Plugins --}}
@section('plugins')
    <link rel="stylesheet" href="{{ asset('css/token-despense.css') }}">
    <link rel="stylesheet" type="text/css" href="https://flatlogic.github.io/awesome-bootstrap-checkbox/demo/build.css" />
@endsection

{{-- Your CSS --}}
@section('css')
<style>
       .round {
            position: relative;
        }

        .round label {
            background-color: #fff;
            border: 1px solid #656363;
            border-radius: 50%;
            cursor: pointer;
            height: 20px;
            left: 0;
            position: absolute;
            top: 2;
            width: 20px;
        }

        .round label:after {
            border: 2px solid #fff;
            border-top: none;
            border-right: none;
            content: "";
            height: 3px;
            left: 4px;
            opacity: 0;
            position: absolute;
            top: 5px;
            transform: rotate(-41deg);
            width: 8px;
        }

        .round input[type="checkbox"] {
            visibility: hidden;
        }

        .round input[type="checkbox"]:checked + label {
            background-color: #66bb6a;
            border-color: #66bb6a;
        }

        .round input[type="checkbox"]:checked + label:after {
            opacity: 1;
        }
     
</style>
@endsection

{{-- Define the content to be included in the 'content' section --}}
@section('content')
    <div class="main-container">
        <div class="container">
            <div class="header">
                <h1 style="font-size: 17px;">Gachapon Token Dispenser</h1>
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
                    
                </div>
    
                <button class="button-pushable btn-swap" role="button">
                    <span class="button-shadow"></span>
                    <span class="button-edge btn-swap-edge"></span>
                    <span class="button-front text btn-swap">
                        <i style="margin-right: 10px;" class="fa-solid fa-rotate"></i>
                        Dispense
                    </span>
                </button>
    
            </form>
        </div> 

        {{-- PRESET SECTION --}}
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
      $('#jan_number').select2({
          width:'100%',
          theme: 'default', // Ensures the default Select2 styles are applied
      });

      var listOfMOP = [];
      var modeOfPayments = {!! json_encode($mode_of_payments) !!};
    
      modeOfPayments.forEach(item => {
        listOfMOP.push(item.id);
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

        const allToken = currentTokenValue + presetValue;
        const tokenValue = allToken * {{ $cash_value }};

        $("#cash_value").val((tokenValue).toLocaleString());
        $("#token_value").val((allToken).toLocaleString());
        $("#total_value").val((tokenValue).toLocaleString());
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
    
      console.log(listOfMOP);
      $('#mode_of_payment_description').text(selectedDescription);
      $('#mode_of_payment_description').hide();

        if(selectedValue == 32){
          $('#payment_reference').hide();
          $('.jan-number-div').fadeIn(1000);
          $('#reference').text("JAN Number:"); 
          $('.addons-container').hide();
          $('#amount_received').hide(); 
          $('#payment_reference_div').fadeIn(1000);

        }else if(listOfMOP.includes(selectedValue)){
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
        }else {
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
    }).on('select2:select', function (e) {

      const data = e.params.data;
      $('.jan-item').val(data.description);
      const existingObjectIndex = jan_data.findIndex(obj => obj.jan_number === data.item);

      if (existingObjectIndex !== -1) {
          jan_data[existingObjectIndex].qty++;
          $(`#${data.item}`).text(jan_data[existingObjectIndex].qty);
          $(`input[inputjanqty="${data.item}"]`).val(jan_data[existingObjectIndex].qty);
          $(`input[no_of_tokens="${data.item}"]`).val(jan_data[existingObjectIndex].qty * data.no_of_tokens);
      } else {
          jan_data.push({
              "jan_number": data.item,
              'qty': 1,
              'no_of_tokens': data.no_of_tokens
          });


          const jan_obj = jan_data.find( (e) => e.jan_number === data.item )
          const htmlContent = `
              <tr data-row="">
                  <td>
                      <input type="hidden" name="jan_number[]" value="${data.item}">
                      <input type="hidden" name="jan_description[]" value="${data.description}">
                      <input type="hidden" inputjanqty="${data.item}" name="jan_qty[]" value="${jan_obj.qty}">
                      <input type="hidden" id='no_of_tokens' no_of_tokens="${data.item}" name="no_of_tokens[]" value="${jan_obj.no_of_tokens}">
                      <span>${data.item} - ${data.description} (${data.no_of_tokens})</span>
                  </td>
                  <td>
                      <span id="${data.item}">${jan_obj.qty}</span>
                  </td>
                  <td>
                      <i class="fas fa-minus-circle janLessQty" less-id="${data.item}" data-id=""></i>
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

    function removeJanQuantity(attrJanNumber){
      const data = jan_data.find( (obj) => obj.jan_number === attrJanNumber)
        data.qty--
        $(`#${attrJanNumber}`).text(data.qty);
        $(`input[inputjanqty="${attrJanNumber}"]`).val(data.qty);
        $(`input[no_of_tokens="${attrJanNumber}"]`).val(data.no_of_tokens/data.qty);

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
            jan_obj.description = row.find('input[name="jan_description[]"]').val();
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
            else if(cashValue > amountReceived && !listOfMOP.includes($('#mode_of_payment').val())){
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
            else if( ($('#payment_reference').val() === '' && listOfMOP.includes($('#mode_of_payment').val())) && $('#mode_of_payment').val() != 32) {
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

                const addOnsSummary = 
                        (addOns.length != 0 ? 
                            '<table class="styled-table-swap">'+
                            '<tr><td colspan="2">Addons</td></tr>' +
                            '<tr><td>Description</td><td>Quantity</td> </tr>' +
                        addOns.map(item => `<tr><td>${item.description}</td><td>${item.qty}</td></tr>`).join('') +
                            '</table>' : '');
                const janNumberSummary = 
                        (janNumber.length != 0 ? 
                            '<table class="styled-table-swap">'+
                            '<tr><td colspan="3">Defective Return</td></tr>' +
                            '<tr><td>Jan Number</td><td style="color:#C33A3A">Description</td><td style="color:black">Quantity</td> </tr>' +
                        janNumber.map(item => `
                                <tr>
                                    <td> ${item.jan_number}</td>
                                    <td style="color:#C33A3A"> ${item.description} </td>
                                    <td style="color:black">${item.qty}</td>
                                </tr>`).join('') +
                            '</table>' : '');
                            

                const summaryTable = `
                    <div class="swal-table-container">
                        <table class="styled-table-swap">
                            <tr>
                                <td>Number of Tokens</td>
                                <td>
                                    ${$('#token_value').val().replace(/\B(?=(\d{3})+(?!\d))/g,",")}
                                </td>
                            </tr>
                            <tr>
                                <td>Mode of Payment</td><td>
                                    ${$('#mode_of_payment_description').text()}
                                    </td>
                            </tr>` 
                            +
                            ($('#mode_of_payment').val() == 1 ? 
                            '<tr><td>Amount Received</td><td>' + 
                                $('#amount_received').val().replace(/\B(?=(\d{3})+(?!\d))/g,",") + 
                            '</td></tr>' : '')  +

                            '<tr><td>Total</td><td>'+ 
                                $('#total_value').val().replace(/\B(?=(\d{3})+(?!\d))/g,",") +
                            '</td></tr>' +

                            
                        '</table>'+ 
                         addOnsSummary + 
                         janNumberSummary +
                    '</div>'
                ;

                Swal.fire({
                    title: 'Are you sure you want to continue?',
                    icon: 'info',
                    customClass: ((janNumber.length != 0 || addOns.length != 0) ? 'swal-container2' : ''),
                    allowOutsideClick: false,
                    html: summaryTable,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Submit',
                    returnFocus: false,
                    reverseButtons: true,
                }).then((save) => {
                 
                    if (save.isConfirmed) {
                        // setTimeout(() => {
                        Swal.fire({
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            title: "Please wait while saving...",
                            didOpen: () => Swal.showLoading()
                        });
                        return $.ajax({
                            type: 'POST',
                            url: "{{ route('swap-dispense') }}",
                            data: formData,
                            success: function(res) {
                                const data = JSON.parse(res);
                            // swal.stopLoading();
                            // swal.close();
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
                                    '</table>' +
                                    addOnsSummary + 
                                    janNumberSummary + 
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
                            $('.btn-swap').css({"background-color": "#00a65a", "border-radius": "12px"});
                            $('.btn-swap').attr('disabled', false);
                    }   
                })

            }
        });

        function loadResult(data){

        }
    });
  </script>
@endsection