<x-guest-layout>
    @section('title', 'Login')

    <form method="POST" action="/login">
        @csrf

        <div class="container">
            <div class="row justify-content-center align-items-center" style="height: 100vh">
                <div class="col-12 col-md-7 col-lg-4">
                    <div class="text-center">
                        <i class="mdi mdi-account-circle text-primary display-1"></i>
                        <h3 class="text-primary">ACCOUNT LOGIN</h3>
                    </div>
                    <div class="card card-body border-0 shadow">
                        <div class="my-1">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{old('email')}}">
                            @error('email')
                            <div id="email" class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="my-1">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" value="{{old('password')}}">
                            @error('password')
                            <div id="password" class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="row mt-3 align-items-center">
                            <div class="col-6">
                                <button class="btn btn-primary">
                                    <i class="mdi mdi-login"></i>
                                    Login
                                </button>
                            </div>
                            <div class="col-6 text-end">
                                <a href="">Forget password ?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</x-guest-layout>