<x-app-layout>
  @section('title', 'Users')
  <x-slot name="breadcrumbs">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
          <a href="/dashboard">
            <i class="mdi mdi-home"></i>
          </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Users</li>
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
            <h3 class="card-title m-0 text-primary">Users Table</h3>
          </div>
          <div class="col-12 col-lg-6 px-0 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddUser">
              <i class="mdi mdi-plus" style="font-size: 20px"></i>
              Add User
            </button>
          </div>
        </div>
      </div>
      <table class="table w-100 table-striped yajra-datatable">
        <thead>
          <tr>
            <th class="bg-primary text-white" style="width: 20px">No</th>
            <th class="bg-primary text-white" style="width: 100px">Email</th>
            <th class="bg-primary text-white" style="width: 150px">Name</th>
            <th class="bg-primary text-white" style="width: 100px">Role</th>
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
  <div class="modal fade" id="modalAddUser" tabindex="-1" aria-labelledby="modalAddUserLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="/users" method="post">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5 text-primary fs-bold" id="modalAddUserLabel">Add User</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="my-2">
              <label for="name" class="form-label">Name</label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                value="{{old('name')}}">
              @error('name')
              <div id="name" class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
            </div>
            <div class="my-2">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                value="{{old('email')}}">
              @error('email')
              <div id="email" class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
            </div>
            <div class="my-2">
              <label for="role" class="form-label">Role</label>
              <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
                <option value=""></option>
                @role('super-admin')
                <option @if(old('role')==='admin' ) selected @endif value="admin">Admin</option>
                @endrole
                <option @if(old('role')==='warehouse' ) selected @endif value="warehouse">Warehouse</option>
              </select>
              @error('role')
              <div id="role" class="invalid-feedback">
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
  <div class="modal fade" id="modalUpdateUser" tabindex="-1" aria-labelledby="modalUpdateUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form action="/users" method="post">
        @csrf
        @method('patch')
        <input type="hidden" name="id" id="id_update">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5 text-primary fs-bold" id="modalUpdateUserLabel">Update Product</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="my-2">
              <label for="name_update" class="form-label">Name</label>
              <input type="text" class="form-control @error('name_update') is-invalid @enderror" id="name_update"
                name="name_update" value="{{old('name_update')}}">
              @error('name_update')
              <div id="name_update" class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
            </div>
            <div class="my-2">
              <label for="email_update" class="form-label">Email</label>
              <input type="email" class="form-control @error('email_update') is-invalid @enderror" id="email_update"
                name="email_update" value="{{old('email_update')}}">
              @error('email_update')
              <div id="email_update" class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
            </div>
            <div class="my-2">
              <label for="role_update" class="form-label">Role</label>
              <select class="form-select @error('role_update') is-invalid @enderror" id="role_update"
                name="role_update">
                <option value=""></option>
                @role('super-admin')
                <option @if(old('role_update')==='admin' ) selected @endif value="admin">Admin</option>
                @endrole
                <option @if(old('role_update')==='warehouse' ) selected @endif value="warehouse">Warehouse</option>
              </select>
              @error('role_update')
              <div id="role_update" class="invalid-feedback">
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
  <form action="/users" method="post" id="form-delete">
    @csrf
    @method('delete')
    <input type="hidden" name="id" id="id-delete">
  </form>
  {{-- end form delete --}}

  @section('scripts')
  @if (session('status') === 'create')
  <script>
    window.addEventListener("DOMContentLoaded", event => {
      new window.bootstrap.Modal('#modalAddUser', {}).show()
    })
  </script>
  @elseif(session('status') === 'update')
  <script>
    window.addEventListener("DOMContentLoaded", event => {
      new window.bootstrap.Modal('#modalUpdateUser', {}).show()
    })
  </script>
  @endif
  <script>
    window.addEventListener("DOMContentLoaded", event => {
      const table = $('.yajra-datatable').DataTable({
          processing: true,
          serverSide: true,
          ajax: "/users/read",
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex', class: 'fw-bold'},
              {data: 'email', name: 'email'},
              {data: 'name', name: 'name'},
              {data: 'role', name: 'role'},
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
      if(dataset.name && dataset.role && dataset.email && dataset.id){
        document.querySelector("#name_update").value = dataset.name
        document.querySelector("#email_update").value = dataset.email
        document.querySelector("#role_update").value = dataset.role
        document.querySelector("#id_update").value = dataset.id
      }
      const modalUpdateUser = new window.bootstrap.Modal('#modalUpdateUser', {})
      modalUpdateUser.show()
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