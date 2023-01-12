@php
$amounts = [];
foreach ($transaction->products as $product) {
$amounts[] = $product->pivot->amount;
}
$valueProducts = old('products') ? old('products') : $transaction->products->pluck('id')->toArray();
$valueStatus = old("status") ? old('status') : $transaction->status;
$valueSupplier = old("supplier") ? old('supplier') : $transaction->supplier_id;
$valueNote = old("note") ? old('note') : $transaction->note;
$valueAmounts = old('amounts') ? old('amounts') : $amounts;
@endphp

<x-app-layout>
  @section('title', 'Update Transaction')
  <x-slot name="breadcrumbs">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
          <a href="/dashboard">
            <i class="mdi mdi-home"></i>
          </a>
        </li>
        <li class="breadcrumb-item">
          <a href="/transactions">Transactions</a>
        </li>
        <li class="breadcrumb-item">
          <a href="/transactions/{{$transaction->id}}">Detail Transaction</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Update Transaction</li>
      </ol>
    </nav>
  </x-slot>

  {{-- content --}}
  <div class="card w-100 shadow border-0">
    <div class="card-body">
      <h3 class="ms-2 text-primary">Update Transaction</h3>
      <form action="/transactions" method="post">
        @csrf
        @method('patch')
        <input type="hidden" name="id" value="{{$transaction->id}}">
        <div class="container">
          <div class="row">
            <div class="col-12 col-lg-6">
              <div class="my-2">
                <label for="status" class="form-label">Status</label>
                <select class="form-select @error('status') is-invalid @enderror" name="status" id="status"
                  onchange="handleChangeStatus(event)">
                  <option selected></option>
                  <option @if ($valueStatus=='1' ) selected @endif value="1">Incoming Transaction</option>
                  <option @if ($valueStatus=='0' ) selected @endif value="0">Outgoing Transaction</option>
                </select>
                @error('status')
                <div id="status" class="invalid-feedback">
                  {{$message}}
                </div>
                @enderror
              </div>
              <div class="my-2 @if($valueStatus != '1') d-none @endif" id="container-supplier">
                <label for="supplier" class="form-label">Supplier</label>
                <select class="form-select @error('supplier') is-invalid @enderror" name="supplier" id="supplier">
                  <option selected></option>
                  @foreach ($suppliers as $supplier)
                  <option value="{{$supplier->id}}" @if($valueSupplier==$supplier->id) selected
                    @endif>{{$supplier->name}}
                  </option>
                  @endforeach
                </select>
                @error('supplier')
                <div id="supplier" class="invalid-feedback">
                  {{$message}}
                </div>
                @enderror
              </div>
              <div class="my-2">
                <label for="note" class="form-label">Note</label>
                <textarea rows="3" class="form-control @error('note') is-invalid @enderror" id="note"
                  name="note">{{$valueNote}}</textarea>
                @error('note')
                <div id="note" class="invalid-feedback">
                  {{$message}}
                </div>
                @enderror
              </div>
            </div>
            <div class="my-2 col-12 col-md-6">
              <label for="products" class="form-label">Products</label>
              <select multiple class="d-none form-select @error('products') is-invalid @enderror" name="products[]"
                id="products">
                @foreach ($products as $product)
                <option value="{{$product->id}}" @if (in_array($product->id, $valueProducts))
                  selected @endif>{{$product->name}}</option>
                @endforeach
              </select>
              @error('products')
              <div id="products" class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
              <ul class="list-group" id="list-product">
                @foreach ($valueProducts as $i => $product_id)
                <li class="list-group-item d-flex align-items-center justify-content-between"
                  id="product-{{$product_id}}">
                  <p class="m-0">
                    @foreach ($products as $item)
                    @if ($item->id == $product_id)
                    {{$item->name}}
                    @endif
                    @endforeach
                    x {{$valueAmounts[$i]}}
                  </p>
                  <i class="mdi mdi-delete text-danger" data-index="{{$product_id}}" style="cursor: pointer"
                    onclick="deleteProduct(event)"></i>
                </li>
                @endforeach
              </ul>
              <div class="text-end">
                <button class="btn-success btn btn-sm mt-3" type="button" data-bs-toggle="modal"
                  data-bs-target="#modalAddProduct">
                  <i class="mdi mdi-plus" style="font-size: 15px"></i>
                  Add Product
                </button>
              </div>
            </div>
          </div>
          <button class="btn btn-primary" type="submit">
            <i class="mdi mdi-content-save" style="font-size: 20px"></i>
            Save
          </button>
        </div>
        <select multiple class="d-none" name="amounts[]" id="amounts">
          @foreach ($valueAmounts as $i => $amount)
          <option value="{{$amount}}" selected id="amount-{{$valueProducts[$i]}}"></option>
          @endforeach
        </select>
      </form>
    </div>
  </div>


  {{-- end content --}}

  {{-- modal add product --}}
  <div class="modal fade" id="modalAddProduct" tabindex="-1" aria-labelledby="modalAddProductLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form class="needs-validation g-3" novalidate>
          <div class="modal-header">
            <h1 class="modal-title fs-5 text-primary" id="modalAddProductLabel">Add Product Transaction</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="my-2">
              <label for="product" class="form-label">Product</label>
              <select class="form-select" id="product" required>
                <option selected disabled></option>
                @foreach ($products as $product)
                <option value="{{$product->id}}">{{$product->name}}</option>
                @endforeach
              </select>
              <div class="invalid-feedback" id="error-message-product">

              </div>
            </div>
            <div class="my-2">
              <label for="amount" class="form-label">Amount</label>
              <input type="number" required class="form-control" id="amount" name="amount">
              <div class="invalid-feedback">
                The amount field is required.
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
              <i class="mdi mdi-close"></i>
              Close
            </button>
            <button type="submit" class="btn btn-primary" onclick="addProduct(event)">
              <i class="mdi mdi-content-save"></i>
              Save
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  {{-- end modal add product --}}


  @section('scripts')
  <script>
    function handleChangeStatus(event) {
      const containerSupplier = document.querySelector('#container-supplier')
      if(event.target.value == 1){
        containerSupplier.classList.remove('d-none')
      }else{
        containerSupplier.classList.add('d-none')
      }
    }

    function addProduct(event) {
      event.preventDefault()
      const forms = [
        document.querySelector("#product"),
        document.querySelector("#amount")
      ]
      const products = document.querySelectorAll('#products option')
      const amounts = document.querySelector('#amounts')
      const errorMessageProduct = document.querySelector('#error-message-product')
      let status = true
      forms.forEach(form => {
        if(!form.value){
          form.classList.add("is-invalid")
          errorMessageProduct.innerText = 'The product field is required.'
          status = false
        }else{
          form.classList.remove("is-invalid")
        }
      });
      const listProduct = document.querySelectorAll("#list-product li")
      listProduct.forEach(product => {
        if(product.getAttribute('id') === `product-${forms[0].value}`){
          status = false
          forms[0].classList.add("is-invalid")
          errorMessageProduct.innerText = 'The product field is already.'
        }
      });
      if(status){
        let product = ''
        let amount = ''
        products.forEach((_product, i) => {
          if(_product.value == forms[0].value){
            _product.setAttribute('selected', '')
            const option = document.createElement('option')
            option.setAttribute('selected', '')
            option.setAttribute('value', forms[1].value)
            option.setAttribute('id', `amount-${forms[0].value}`)
            amounts.appendChild(option)
            amount = forms[1].value
            product = _product.innerText
          }
        });
        const containerListProduct = document.querySelector("#list-product")  
        const li = templateItemProduct({product, amount, index: forms[0].value})
        containerListProduct.appendChild(li)
        forms[0].value = ''
        forms[1].value = ''
        const modalAddProduct = document.querySelector('#modalAddProduct');
        const modal = bootstrap.Modal.getInstance(modalAddProduct);    
        modal.hide();
      }
    }

    function templateItemProduct({product, amount, index}) {
      const className = {
        li: ['list-group-item', 'd-flex', 'align-items-center', 'justify-content-between'],
        p: ['m-0'],
        i: ['mdi', 'mdi-delete', 'text-danger'],
      }
      const li = document.createElement("li")
      const p = document.createElement("p")
      const i = document.createElement("i")
      className.li.forEach(cls => {
        li.classList.add(cls)
      });
      className.p.forEach(cls => {
        p.classList.add(cls)
      });
      p.innerText = `${product} x ${amount}`
      className.i.forEach(cls => {
        i.classList.add(cls)
      });
      i.style = 'cursor: pointer'
      i.setAttribute('onclick', 'deleteProduct(event)')
      i.setAttribute('data-index', index)
      li.setAttribute('id', `product-${index}`)
      li.appendChild(p)
      li.appendChild(i)
      return li
    }

    function deleteProduct(event) {
      const index = event.target.dataset.index
      const containerListProduct = document.querySelector("#list-product") 
      const listProduct = document.querySelectorAll("#list-product li")
      const product = document.querySelector(`#product-${index}`)
      containerListProduct.removeChild(product)

      const optionProduct = document.querySelector(`#products option[value='${index}']`)
      optionProduct.removeAttribute('selected')

      const optionAmount = document.querySelector(`#amounts #amount-${index}`)
      optionAmount.removeAttribute('selected')
    }
  </script>
  @endsection
</x-app-layout>