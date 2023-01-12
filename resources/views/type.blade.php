<x-app-layout>
  @section('title', 'Types')
  <x-slot name="breadcrumbs">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
          <a href="/dashboard">
            <i class="mdi mdi-home"></i>
          </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Types</li>
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
            <h3 class="card-title m-0 text-primary">Types Table</h3>
          </div>
          <div class="col-12 col-lg-6 px-0 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddType">
              <i class="mdi mdi-plus" style="font-size: 20px"></i>
              Add Type
            </button>
          </div>
        </div>
      </div>
      <table class="table w-100 table-striped yajra-datatable">
        <thead>
          <tr>
            <th class="bg-primary text-white" style="width: 20px">No</th>
            <th class="bg-primary text-white" style="width: 200px">Name</th>
            <th class="bg-primary text-white">Created At</th>
            <th class="bg-primary text-white">Updated At</th>
            <th class="bg-primary text-white text-center" style="width: 165px">Action</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
  {{-- end content --}}

  {{-- modal add --}}
  <div class="modal fade" id="modalAddType" tabindex="-1" aria-labelledby="modalAddTypeLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="/types" method="post">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5 text-primary fs-bold" id="modalAddTypeLabel">Add Type</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div>
              <label for="name" class="form-label">Name</label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                value="{{old('name')}}">
              @error('name')
              <div id="name" class="invalid-feedback">
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
  <div class="modal fade" id="modalUpdateType" tabindex="-1" aria-labelledby="modalUpdateTypeLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="/types" method="post">
        @csrf
        @method('patch')
        <input type="hidden" name="id" id="id_update">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5 text-primary fs-bold" id="modalUpdateTypeLabel">Update Type</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div>
              <label for="name_update" class="form-label">Name</label>
              <input type="text" class="form-control @error('name_update') is-invalid @enderror" id="name_update"
                name="name_update" value="{{old('name_update')}}">
              @error('name_update')
              <div id="name_update" class="invalid-feedback">
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
  <form action="/types" method="post" id="form-delete">
    @csrf
    @method('delete')
    <input type="hidden" name="id" id="id-delete">
  </form>
  {{-- end form delete --}}

  @section('scripts')
  @if ($errors->get('name'))
  <script>
    window.addEventListener("DOMContentLoaded", event => {
      new window.bootstrap.Modal('#modalAddType', {}).show()
    })
  </script>
  @elseif($errors->get('name_update'))
  <script>
    window.addEventListener("DOMContentLoaded", event => {
      new window.bootstrap.Modal('#modalUpdateType', {}).show()
    })
  </script>
  @endif
  <script>
    window.addEventListener("DOMContentLoaded", event => {
      const table = $('.yajra-datatable').DataTable({
          processing: true,
          serverSide: true,
          ajax: "/types/read",
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex', class: 'fw-bold'},
              {data: 'name', name: 'name'},
              {data: 'created_at', name: 'created_at'},
              {data: 'updated_at', name: 'updated_at'},
              {
                  data: 'action', 
                  name: 'action', 
                  orderable: false, 
                  searchable: false
              },
          ]
      });
    })
    function showModalUpdate(event) {
      const dataset = event.target.dataset
      document.querySelector("#name_update").value = dataset.name
      document.querySelector("#id_update").value = dataset.id
      const modalUpdateType = new window.bootstrap.Modal('#modalUpdateType', {})
      modalUpdateType.show()
    }

    function destroy(id){
      window.Swal.fire({
        title: 'Confirm',
        text: 'Do you want to delete type ?',
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