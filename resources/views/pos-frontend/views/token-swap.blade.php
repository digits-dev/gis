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
    /* background-color: #f1f1f1; */
    margin-top: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: "Open Sans", sans-serif;
    
  }
  .container {
    width: 460px;
    height: 550px;
    background-color: white;
    box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px,
      rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
    border-radius: 30px;
    padding: 20px 30px;
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
  }
  label i {
    font-size: 20px;
  }
  .container select {
    font-weight: bold;
    background-color: rgb(255, 255, 255);
    border: none;
    padding: 18px;
    color: black;
    outline: none;
    cursor: pointer;
  }
  .container option {
    font-size: 14px;
  }
  .container form input {
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
    margin-top: 20px;
  }
  .mode-of-payment select {
    width: 100%;
    background: none;
    cursor: pointer;
  }
  .summary {
    display: flex;
    justify-content: space-evenly;
    margin: 20px 0;
  }
  .summary-value {
    gap: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }
  .total,
  .change {
    display: flex;
    justify-content: center;
    padding: 6px 12px;
    width: 100px;
    height: 40px;
    border-radius: 10px;
    border: 2px solid #e60213;
    background-color: #ffbfbf;
    /* font-size: 19px; */
  }
  button {
    cursor: pointer;
    width: 100%;
    height: 40px;
    font-size: 20px;
    font-weight: bold;
    background-color: #e60213;
    border: none;
    color: white;
    border-radius: 10px;
  }

  .summary-value span{
    font-size: 19px;
  }
</style>
@endsection

{{-- Define the content to be included in the 'content' section --}}
@section('content')
<div class="main-container">
    <div class="container">
        <div class="header">
            <h1 style="font-size: 19px;">Swap</h1>
            <p>P65.00 per token</p>
        </div>
        <form id="myForm" action="">
            <div>
                <label>
                    <i class="fa-solid fa-peso-sign sign1"></i>
                    <i class="fa-solid fa-coins coin1"></i>
                </label>
                <select name="currency" id="float-select">
                    <option value="peso">Peso</option>
                    <option value="token">Token</option>
                </select>
            </div>
            <div class="amount" id="div1">
                <input type="text" name="float1" id="float1" oninput="onInput1()" />
            </div>
            <div class="btn-reverse" id="btn-reverse">
                <i
                class="fa-solid fa-right-left btn-reverse-icon"
                onclick="swap()">
                </i>
            </div>
            <div div id="float-wrapper2">
                <label>
                    <i class="fa-solid fa-peso-sign sign2"></i>
                    <i class="fa-solid fa-coins coin2"></i>
                </label>
                <select name="currency" id="float-select2">
                    <option value="token">Token</option>
                    <option value="peso">Peso</option>
                </select>
            </div>
            <div class="from" id="div2">
                <input type="text" name="float2" id="float2" oninput="onInput2()" />
            </div>
            <div div class="mode-of-payment">
                <select name="mode of payment" id="mode-of-payment">
                    <option value="" disabled selected>Mode of Payment</option>
                    <option value="token">Gcash</option>
                    <option value="peso">Card</option>
                </select>
            </div>
            <div class="summary">
                <div class="summary-value">
                    <span>Total</span>
                    <div class="total"></div>
                </div>
                <div class="summary-value">
                    <span>Change</span>
                    <div class="change"></div>
                </div>
            </div>
            <button type="submit">Swap</button>
        </form>
    </div> 
</div>
@endsection

{{-- Your Script --}}

@section('script-js')
<script>
    const selectElement = document.getElementById("float-select");
    const selectElement2 = document.getElementById("float-select2");

    // icons
    const signIcon = document.querySelector(".sign1");
    const signIcon2 = document.querySelector(".sign2");
    const coinIcon = document.querySelector(".coin1");
    const coinIcon2 = document.querySelector(".coin2");

    const float1Input = document.getElementById("float1");
    const float2Input = document.getElementById("float2");
    const changeElement = document.querySelector(".change");
    const totalElement = document.querySelector(".total");

    function updateIcons() {
      toggleIcon(signIcon, selectElement.value === "peso");
      toggleIcon(coinIcon, selectElement.value !== "peso");
      toggleIcon(signIcon2, selectElement2.value === "peso");
      toggleIcon(coinIcon2, selectElement2.value !== "peso");

      function toggleIcon(icon, condition) {
        icon.style.display = condition ? "inline" : "none";
      }
    }

    updateIcons();

    selectElement.addEventListener("change", updateIcons);
    selectElement2.addEventListener("change", updateIcons);

    function onInput1() {
      const float1Value = parseFloat(float1Input.value);
      const converted = Math.floor(float1Value / 65);
      const remainder = float1Value % 65;
      const total = converted * 65;

      if (float1Value) {
        float2Input.value = converted;
        changeElement.textContent = remainder;
        totalElement.textContent = total;
      } else {
        float2Input.value = "";
        changeElement.textContent = "";
        totalElement.textContent = "";
      }
    }

    function onInput2() {
      const float2Value = float2Input.value;
      const converted = float2Value * 65;
      if (float2Value) {
        float1Input.value = converted;
        totalElement.textContent = converted;
      } else {
        float1Input.value = "";
        changeElement.textContent = "";
      }
    }

    let isSwapped = false;

    function swap() {
      const op1 = selectElement.value;
      const op2 = selectElement2.value;

      selectElement2.value = op1;
      selectElement.value = op2;

      const div1 = document.getElementById("div1");
      const div2 = document.getElementById("div2");
      const btnReverse = document.getElementById("btn-reverse");
      const floatWrapper2 = document.getElementById("float-wrapper2");
      const parent = div1.parentNode;

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

      updateIcons();
    }

    document.getElementById("myForm").addEventListener("submit", function () {
      event.preventDefault();

      const float1 = document.getElementById("float1").value;
      const float2 = document.getElementById("float2").value;

      console.log(float1 + float2);
    });
  </script>
@endsection