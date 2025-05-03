@extends('pos-frontend.components.content')

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

  .responsive_table {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
    font-family: 'Inter', 'Open Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    color: var(--gray-800);
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

  .highlight {
    color: var(--primary);
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin: 1.5rem 0;
    background-color: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
  }

  thead {
    background-color: var(--primary);
    color: white;
  }

  th {
    text-align: left;
    padding: 1rem;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  td {
    padding: 1rem;
    border-bottom: 1px solid var(--gray-200);
    font-size: 0.95rem;
  }

  tbody tr:nth-child(even) {
    background-color: var(--gray-100);
  }

  tbody tr:hover {
    background-color: var(--gray-200);
  }

  tfoot {
    background-color: white;
  }

  tfoot td {
    padding: 1rem;
    border-bottom: none;
  }

  .summary-row td {
    font-weight: 700;
    border-top: 2px solid var(--primary);
    background-color: var(--gray-100);
  }

  .summary-card {
    background-color: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
  }

  .summary-title {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: var(--gray-800);
  }

  .summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
  }

  .summary-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
  }

  .summary-label {
    font-size: 0.875rem;
    color: var(--gray-600);
  }

  .summary-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary);
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

  .btn-back:hover {
    background-color: var(--gray-100);
    border-color: var(--gray-600);
  }

  .btn-back:active {
    transform: translateY(1px);
  }

  @media (max-width: 768px) {
    .transaction-details {
      grid-template-columns: 1fr;
    }
    
    table {
      display: block;
      overflow-x: auto;
    }
  }
</style>

@section('content')
<div class="responsive_table">
  <div class="transaction-header">
    <div class="transaction-title">
      Transaction Details
    </div>
    <div class="transaction-details">
      <div class="detail-group">
        <div class="detail-item">
          <span class="detail-label">Reference #</span>
          <span class="detail-value">{{$items->reference_number}}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Total Value</span>
          <span class="detail-value highlight">{{$items->total_value}}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Change Value</span>
          <span class="detail-value">{{$items->change_value}}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Mode of Payment</span>
          <span class="detail-value">{{$items->ModeOfPayments->payment_description}}</span>
        </div>
      </div>
      <div class="detail-group">
        <div class="detail-item">
          <span class="detail-label">Location</span>
          <span class="detail-value">{{$items->location->location_name}}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Payment Reference</span>
          <span class="detail-value">{{$items->payment_reference}}</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Status</span>
          <span class="detail-value highlight">{{$items->status}}</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Summary Card -->
  <div class="summary-card">
    <div class="summary-title">Order Summary</div>
    <div class="summary-grid">
      <div class="summary-item">
        <span class="summary-label">Total Quantity</span>
        <span class="summary-value">
          @php
            $totalQty = 0;
            foreach($items->item_lines as $row) {
              // Assuming the quantity field is named 'quantity' or 'qty'
              // Adjust the field name as needed based on your actual data structure
              $qtyField = isset($row->quantity) ? 'quantity' : (isset($row->qty) ? 'qty' : null);
              if ($qtyField && isset($row->$qtyField)) {
                $totalQty += $row->$qtyField;
              }
            }
            echo $totalQty;
          @endphp
        </span>
      </div>
      <div class="summary-item">
        <span class="summary-label">Total Price</span>
        <span class="summary-value">
          @php
            $totalPrice = 0;
            foreach($items->item_lines as $row) {
              $priceField = isset($row->total_price) ? 'total_price' : (isset($row->price) ? 'price' : (isset($row->amount) ? 'amount' : null));
              if ($priceField && isset($row->$priceField)) {
                $totalPrice += $row->$priceField;
              }
            }
            echo number_format($totalPrice, 2);
          @endphp
        </span>
      </div>
    </div>
  </div>

  <table>
    <thead>
      <tr>
        @foreach(array_keys($items->item_lines[0]->getAttributes()) as $key)
          @if(!in_array($key, ['id','item_pos_id','status','created_by','updated_by','created_at','updated_at','deleted_at','locations_id']))
            <th>{{ ucwords(str_replace('_', ' ', $key)) }}</th>
          @endif
        @endforeach
      </tr>
    </thead>

    <tbody>
      @foreach($items->item_lines as $row)
        <tr>
          @foreach($row->getAttributes() as $key => $value)
            @if(!in_array($key, ['id','item_pos_id','status','created_by','updated_by','created_at','updated_at','deleted_at','locations_id']))
              <td>{{ $value }}</td>
            @endif
          @endforeach
        </tr>
      @endforeach
    </tbody>

    <tfoot>
      <!-- Summary Row in Table -->
      <tr class="summary-row">
        @php
          $columnCount = 0;
          $qtyColumnIndex = -1;
          $priceColumnIndex = -1;
          $columns = [];
          
          // First pass: identify column positions and count
          foreach(array_keys($items->item_lines[0]->getAttributes()) as $index => $key) {
            if(!in_array($key, ['id','item_pos_id','status','created_by','updated_by','created_at','updated_at','deleted_at','locations_id'])) {
              $columns[] = $key;
              $columnCount++;
              
              // Try to identify quantity and price columns
              if (in_array($key, ['quantity', 'qty'])) {
                $qtyColumnIndex = count($columns) - 1;
              }
              if (in_array($key, ['total_price', 'price', 'amount'])) {
                $priceColumnIndex = count($columns) - 1;
              }
            }
          }
          
          // Calculate totals
          $totalQty = 0;
          $totalPrice = 0;
          foreach($items->item_lines as $row) {
            if ($qtyColumnIndex >= 0 && isset($row->{$columns[$qtyColumnIndex]})) {
              $totalQty += $row->{$columns[$qtyColumnIndex]};
            }
            if ($priceColumnIndex >= 0 && isset($row->{$columns[$priceColumnIndex]})) {
              $totalPrice += $row->{$columns[$priceColumnIndex]};
            }
          }
        @endphp
        
        @for($i = 0; $i < $columnCount; $i++)
          @if($i == $qtyColumnIndex)
            <td>Total: {{ $totalQty }}</td>
          @elseif($i == $priceColumnIndex)
            <td>Total: {{ number_format($totalPrice, 2) }}</td>
          @elseif($i == 0)
            <td>Summary</td>
          @else
            <td></td>
          @endif
        @endfor
      </tr>
      
      <tr>
        <td colspan="{{ $columnCount }}">
          <button class="btn-back" onclick="goBack()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Back
          </button>
        </td>
      </tr>
    </tfoot>
  </table>
</div>
@endsection

@section('script-js')
<script>
  function goBack() {
    window.history.back();
  }
</script>
@endsection