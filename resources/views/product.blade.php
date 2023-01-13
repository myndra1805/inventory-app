<x-app-layout>
  <style>
    @media screen and (max-width: 991px) {
      #container-table {
        overflow: auto;
      }
    }
  </style>

  @section('title', 'Products')
  <x-slot name="breadcrumbs">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
          <a href="/dashboard">
            <i class="mdi mdi-home"></i>
          </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Products</li>
      </ol>
    </nav>
  </x-slot>

  {{-- flash message --}}
  @if (session('message'))
  <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
    <i class="mdi mdi-check-circle me-2" style="font-size: 20px"></i>
    <div>
      {{session('message')}}
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif
  {{-- end flash message --}}

  {{-- content --}}
  <div class="card w-100 shadow border-0">
    <div class="card-body">
      <div class="container mb-3">
        <div class="row align-items-center">
          <div class="col-12 col-lg-6 px-0">
            <h3 class="card-title m-0 text-primary">Products Table</h3>
          </div>
          <div class="col-12 col-lg-6 px-0 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddProduct">
              <i class="mdi mdi-plus" style="font-size: 20px"></i>
              Add Product
            </button>
          </div>
        </div>
      </div>
      <div id="container-table">
        <table class="table w-100 table-striped yajra-datatable nowrap">
          <thead>
            <tr>
              <th class="bg-primary text-white">No</th>
              <th class="bg-primary text-white">Code</th>
              <th class="bg-primary text-white">Name</th>
              <th class="bg-primary text-white">Created At</th>
              <th class="bg-primary text-white">Updated At</th>
              <th class="bg-primary text-white text-center">Action</th>
              <th class="bg-primary text-white"></th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
  {{-- end content --}}

  {{-- modal add --}}
  <div class="modal fade" id="modalAddProduct" tabindex="-1" aria-labelledby="modalAddProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form action="/products" method="post">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5 text-primary fs-bold" id="modalAddProductLabel">Add Product</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="container">
              <div class="row">
                <div class="col-12 col-md-6 my-2">
                  <label for="name" class="form-label">Name</label>
                  <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    value="{{old('name')}}">
                  @error('name')
                  <div id="name" class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                </div>
                <div class="col-12 col-md-6 my-2">
                  <label for="price" class="form-label">Price</label>
                  <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price"
                    value="{{old('price')}}">
                  @error('price')
                  <div id="price" class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                </div>
                <div class="col-12 col-md-6 my-2">
                  <label for="type" class="form-label">Type</label>
                  <select class="form-select @error('type') is-invalid @enderror" name="type" id="type">
                    <option selected></option>
                    @foreach ($types as $type)
                    <option value="{{$type->id}}" @if(old('type')==$type->id) selected @endif>{{$type->name}}</option>
                    @endforeach
                  </select>
                  @error('type')
                  <div id="type" class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                </div>
                <div class="col-12 col-md-6 my-2">
                  <label for="unit" class="form-label">Units</label>
                  <select class="form-select @error('unit') is-invalid @enderror" name="unit" id="unit">
                    <option selected></option>
                    @foreach ($units as $unit)
                    <option value="{{$unit->id}}" @if(old('unit')==$unit->id) selected @endif>{{$unit->name}}</option>
                    @endforeach
                  </select>
                  @error('unit')
                  <div id="unit" class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
              <i class="mdi mdi-close"></i>
              Close
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="mdi mdi-content-save"></i>
              Save
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
  {{-- end modal add --}}


  {{-- modal update --}}
  <div class="modal fade" id="modalUpdateProduct" tabindex="-1" aria-labelledby="modalUpdateProductLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form action="/products" method="post">
        @csrf
        @method('patch')
        <input type="hidden" name="id" id="id_update">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5 text-primary fs-bold" id="modalUpdateProductLabel">Update Product</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="container">
              <div class="row">
                <div class="col-12 col-md-6 my-2">
                  <label for="name_update" class="form-label">Name</label>
                  <input type="text" class="form-control @error('name_update') is-invalid @enderror" id="name_update"
                    name="name_update" value="{{old('name_update')}}">
                  @error('name_update')
                  <div id="name_update" class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                </div>
                <div class="col-12 col-md-6 my-2">
                  <label for="price_update" class="form-label">Price</label>
                  <input type="number" class="form-control @error('price_update') is-invalid @enderror"
                    id="price_update" name="price_update" value="{{old('price_update')}}">
                  @error('price_update')
                  <div id="price_update" class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                </div>
                <div class="col-12 col-md-6 my-2">
                  <label for="type_update" class="form-label">Type</label>
                  <select class="form-select @error('type_update') is-invalid @enderror" name="type_update"
                    id="type_update">
                    <option selected></option>
                    @foreach ($types as $type)
                    <option value="{{$type->id}}" @if(old('type_update')==$type->id) selected @endif>{{$type->name}}
                    </option>
                    @endforeach
                  </select>
                  @error('type_update')
                  <div id="type_update" class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                </div>
                <div class="col-12 col-md-6 my-2">
                  <label for="unit_update" class="form-label">Units</label>
                  <select class="form-select @error('unit_update') is-invalid @enderror" name="unit_update"
                    id="unit_update">
                    <option selected></option>
                    @foreach ($units as $unit)
                    <option value="{{$unit->id}}" @if(old('unit_update')==$unit->id) selected @endif>{{$unit->name}}
                    </option>
                    @endforeach
                  </select>
                  @error('unit_update')
                  <div id="unit_update" class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
              <i class="mdi mdi-close"></i>
              Close
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="mdi mdi-content-save"></i>
              Save
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
  {{-- end modal update --}}


  {{-- form delete --}}
  <form action="/products" method="post" id="form-delete">
    @csrf
    @method('delete')
    <input type="hidden" name="id" id="id-delete">
  </form>
  {{-- end form delete --}}

  @section('scripts')
  @if (session('status') === 'create')
  <script>
    window.addEventListener("DOMContentLoaded", event => {
      new window.bootstrap.Modal('#modalAddProduct', {}).show()
    })
  </script>
  @elseif(session('status') === 'update')
  <script>
    window.addEventListener("DOMContentLoaded", event => {
      new window.bootstrap.Modal('#modalUpdateProduct', {}).show()
    })
  </script>
  @endif
  <script>
    window.addEventListener("DOMContentLoaded", event => {
      const table = $('.yajra-datatable').DataTable({
          processing: true,
          serverSide: true,
          responsive: true,
          ajax: "/products/read",
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex', class: 'fw-bold', width: "20px"},
              {data: 'product_code', name: 'product_code', width: "120px"},
              {data: 'name', name: 'name'},
              {data: 'created_at', name: 'created_at', width: "120px"},
              {data: 'updated_at', name: 'updated_at', width: "120px"},
              {
                  data: 'action', 
                  name: 'action', 
                  orderable: false, 
                  searchable: false,
                  width: "170px"
              },
              {
                className: 'dt-control',
                orderable: false,
                data: null,
                defaultContent: '',
                searchable: false,
                width: "20px"
              },
          ]
      });
      $('.yajra-datatable').on('click', 'td.dt-control', function () {
        const tr = $(this).closest('tr');
        const row = table.row(tr);

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            row.child(format(row.data())).show();
            tr.addClass('shown');
        }
      });

    })

    function format(d) {
      return /*html*/`
          <tr>
            <th class="p-2">Price</th>
            <td class="p-2">: ${formatRupiah(d.price.toString())}</td>
          </tr>
          <tr>
            <th class="p-2">Unit</th>
            <td class="p-2">: ${d.unit}</td>
          </tr>
          <tr>
            <th class="p-2">Type</th>
            <td class="p-2">: ${d.type}</td>
          </tr>
          <tr>
            <th class="p-2">Amount</th>
            <td class="p-2">: 0</td>
          </tr>
      `;
    }

    function showModalUpdate(event) {
      const dataset = event.target.dataset
      if(dataset.name && dataset.price && dataset.type &&  dataset.unit && dataset.id){
        document.querySelector("#name_update").value = dataset.name
        document.querySelector("#price_update").value = dataset.price
        const types = document.querySelectorAll("#type_update option")
        types.forEach(type => type.removeAttribute('selected'));
        types.forEach(type => {
          if(type.value == dataset.type){
            type.setAttribute('selected', '')
          }
        });
        const units = document.querySelectorAll("#unit_update option")
        units.forEach(unit => unit.removeAttribute('selected'));
        units.forEach(unit => {
          if(unit.value == dataset.unit){
            unit.setAttribute('selected', '')
          }
        });
        document.querySelector("#id_update").value = dataset.id
      }
      const modalUpdateProduct = new window.bootstrap.Modal('#modalUpdateProduct', {})
      modalUpdateProduct.show()
    }

    function destroy(id){
      window.Swal.fire({
        title: 'Confirm',
        text: 'Do you want to delete product ?',
        icon: 'question',
        confirmButtonText: 'Yes',
        confirmButtonColor: 'teal',
        showCancelButton: true
      }).then(response => {
        if(response.isConfirmed){
          document.querySelector("#id-delete").value = id
          document.querySelector("#form-delete").submit()
        }
      })
    }


    function formatRupiah(angka){
			var number_string = angka.replace(/[^,\d]/g, '').toString()
			const split = number_string.split(',')
			const sisa = split[0].length % 3
			let rupiah = split[0].substr(0, sisa)
			const ribuan = split[0].substr(sisa).match(/\d{3}/gi);
 
			if(ribuan){
				const separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
 
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return 'Rp. ' + rupiah + ',00';
		}
  </script>
  @endsection
</x-app-layout>