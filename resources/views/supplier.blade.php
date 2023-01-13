<x-app-layout>
  <style>
    @media screen and (max-width: 991px) {
      #container-table {
        overflow: auto;
      }
    }
  </style>

  @section('title', 'Suppliers')
  <x-slot name="breadcrumbs">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
          <a href="/dashboard">
            <i class="mdi mdi-home"></i>
          </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Suppliers</li>
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
            <h3 class="card-title m-0 text-primary">Suppliers Table</h3>
          </div>
          <div class="col-12 col-lg-6 px-0 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddSupplier">
              <i class="mdi mdi-plus" style="font-size: 20px"></i>
              Add Supplier
            </button>
          </div>
        </div>
      </div>
      <div id="container-table">
        <table class="table w-100 table-striped yajra-datatable nowrap">
          <thead>
            <tr>
              <th class="bg-primary text-white">No</th>
              <th class="bg-primary text-white">Name</th>
              <th class="bg-primary text-white">Created At</th>
              <th class="bg-primary text-white">Updated At</th>
              <th class="bg-primary text-white text-center">Action</th>
              <th class="bg-primary text-white"></th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{-- end content --}}

  {{-- modal add --}}
  <div class="modal fade" id="modalAddSupplier" tabindex="-1" aria-labelledby="modalAddSupplierLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <form action="/suppliers" method="post">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5 text-primary fs-bold" id="modalAddSupplierLabel">Add Supplier</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="my-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                value="{{old('name')}}">
              @error('name')
              <div id="name" class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
            </div>
            <div class="my-3">
              <label for="address" class="form-label">Address</label>
              <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address"
                value="{{old('address')}}">
              @error('address')
              <div id="address" class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
            </div>
            <div class="my-3">
              <label for="phone_number" class="form-label">Phone Number</label>
              <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number"
                name="phone_number" value="{{old('phone_number')}}">
              @error('phone_number')
              <div id="phone_number" class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
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
  <div class="modal fade" id="modalUpdateSupplier" tabindex="-1" aria-labelledby="modalUpdateSupplierLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <form action="/suppliers" method="post">
        @csrf
        @method('patch')
        <input type="hidden" name="id" id="id_update">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5 text-primary fs-bold" id="modalUpdateSupplierLabel">Update Supplier</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="my-3">
              <label for="name_update" class="form-label">Name</label>
              <input type="text" class="form-control @error('name_update') is-invalid @enderror" id="name_update"
                name="name_update" value="{{old('name_update')}}">
              @error('name_update')
              <div id="name_update" class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
            </div>
            <div class="my-3">
              <label for="address_update" class="form-label">Address</label>
              <input type="text" class="form-control @error('address_update') is-invalid @enderror" id="address_update"
                name="address_update" value="{{old('address_update')}}">
              @error('address_update')
              <div id="address_update" class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
            </div>
            <div class="my-3">
              <label for="phone_number_update" class="form-label">Phone Number</label>
              <input type="text" class="form-control @error('phone_number_update') is-invalid @enderror"
                id="phone_number_update" name="phone_number_update" value="{{old('phone_number_update')}}">
              @error('phone_number_update')
              <div id="phone_number_update" class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
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
  <form action="/suppliers" method="post" id="form-delete">
    @csrf
    @method('delete')
    <input type="hidden" name="id" id="id-delete">
  </form>
  {{-- end form delete --}}

  @section('scripts')
  @if (session('status') === 'create')
  <script>
    window.addEventListener("DOMContentLoaded", event => {
      new window.bootstrap.Modal('#modalAddSupplier', {}).show()
    })
  </script>
  @elseif(session('status') === 'update')
  <script>
    window.addEventListener("DOMContentLoaded", event => {
      new window.bootstrap.Modal('#modalUpdateSupplier', {}).show()
    })
  </script>
  @endif
  <script>
    window.addEventListener("DOMContentLoaded", event => {
      const table = $('.yajra-datatable').DataTable({
          processing: true,
          serverSide: true,
          responsive: true,
          ajax: "/suppliers/read",
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex', class: 'fw-bold', width: '20px'},
              {data: 'name', name: 'name'},
              {data: 'created_at', name: 'created_at', width: '120px'},
              {data: 'updated_at', name: 'updated_at', width: '120px'},
              {
                  data: 'action', 
                  name: 'action', 
                  orderable: false, 
                  searchable: false,
                  width: '170px'
              },
              {
                className: 'dt-control',
                orderable: false,
                data: null,
                defaultContent: '',
                searchable: false,
                width: '20px'
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
            <th class="p-2">Address</th>
            <td class="p-2">: ${d.address}</td>
          </tr>
          <tr>
            <th class="p-2">Phone Number</th>
            <td class="p-2">: ${d.phone_number}</td>
          </tr>
      `;
    }

    function showModalUpdate(event) {
      const dataset = event.target.dataset
      if(dataset.name && dataset.address && dataset.phone_number && dataset.id){
        document.querySelector("#name_update").value = dataset.name
        document.querySelector("#address_update").value = dataset.address
        document.querySelector("#phone_number_update").value = dataset.phone_number
        document.querySelector("#id_update").value = dataset.id
      }
      const modalUpdateSupplier = new window.bootstrap.Modal('#modalUpdateSupplier', {})
      modalUpdateSupplier.show()
    }

    function destroy(id){
      window.Swal.fire({
        title: 'Confirm',
        text: 'Do you want to delete supplier ?',
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
  </script>
  @endsection
</x-app-layout>