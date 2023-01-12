<x-app-layout>
  @section('title', 'Profile')
  <x-slot name="breadcrumbs">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
          <a href="/dashboard">
            <i class="mdi mdi-home"></i>
          </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Profile</li>
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
  <div class="container-fuid">
    <div class="card border-0 shadow">
      <div class="card-body">
        <div class="text-center">
          <div class="bg-primary rounded">
            <i class="mdi mdi-account-circle text-white" style="font-size: 150px"></i>
          </div>
          <p class="mb-0 mt-3 h5">{{$user->name}}</p>
          <p class="m-0">{{$user->getRoleNames()[0]}}</p>
          <p class="m-0">{{$user->email}}</p>
        </div>
        <div class="d-flex justify-content-center mt-3">
          <button class="btn btn-primary mx-1" data-bs-toggle="modal" data-bs-target="#modalUpdateProfile">
            <i class="mdi mdi-pencil"></i>
            Update Profile
          </button>
          <button class="btn btn-primary mx-1" data-bs-toggle="modal" data-bs-target="#modalChangePassword">
            <i class="mdi mdi-lock"></i>
            Change Password
          </button>
        </div>
      </div>
    </div>
  </div>
  {{-- end content --}}

  {{-- modal update profile --}}
  <div class="modal fade" id="modalUpdateProfile" tabindex="-1" aria-labelledby="modalUpdateProfileLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <form action="/profile/update-profile" method="post">
        @csrf
        @method('patch')
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5 text-primary" id="modalUpdateProfileLabel">Update Profile</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="my-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                value="{{old('name') ? old('name') : $user->name}}">
              @error('name')
              <div id="name" class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
            </div>
            <div class="my-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                value="{{old('email') ? old('email') : $user->email}}">
              @error('email')
              <div id="email" class="invalid-feedback">
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
  {{-- end modal update profile --}}


  {{-- modal change password --}}
  <div class="modal fade" id="modalChangePassword" tabindex="-1" aria-labelledby="modalChangePasswordLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <form action="/profile/change-password" method="post">
        @csrf
        @method('patch')
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5 text-primary" id="modalChangePasswordLabel">Change Password</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="my-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                name="password" value="{{old('password')}}">
              @error('password')
              <div id="password" class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
            </div>
            <div class="my-3">
              <label for="new_password" class="form-label">New Password</label>
              <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password"
                name="new_password" value="{{old('new_password')}}">
              @error('new_password')
              <div id="new_password" class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
            </div>
            <div class="my-3">
              <label for="new_password_confirmation" class="form-label">New Password Confirmation</label>
              <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror"
                id="new_password_confirmation" name="new_password_confirmation"
                value="{{old('new_password_confirmation')}}">
              @error('new_password_confirmation')
              <div id="new_password_confirmation" class="invalid-feedback">
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
  {{-- end modal change password --}}

  @section('scripts')
  @if (session('status') === 'update-profile')
  <script>
    window.addEventListener("DOMContentLoaded", event => {
      new window.bootstrap.Modal('#modalUpdateProfile', {}).show()
    })
  </script>
  @elseif(session('status') === 'change-password')
  <script>
    window.addEventListener("DOMContentLoaded", event => {
      new window.bootstrap.Modal('#modalChangePassword', {}).show()
    })
  </script>
  @endif
  @endsection

</x-app-layout>