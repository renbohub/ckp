    
    @extends('layouts.landing')
    
    @section('content')
    <style>
        body {
            background-color: rgb(62, 77, 97)!important
        }
    </style>
    <div class="login">
      <!-- BEGIN login-content -->
      <div class="login-content" style="padding-top: 200px">
          <div class="row">
            <div class="col-12 col-md-12 col-lg-6 col-xl-7"></div>
            <div class="col-12 col-md-12 col-lg-5 px-5 col-xl-4">
                <div class="card">
                    <div class="card-body px-5 py-5">
                        <form action="{{route('login-action')}}" method="POST" name="login_form">
                            @csrf
                            <h1 class="text-center">Login</h1>
                            <div class="text-white text-opacity-50 text-center mb-4">
                                For your protection, please verify your identity.
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg bg-white bg-opacity-5" value="" placeholder=""
                                    name="username" require />
                            </div>
                            <div class="mb-3">
                                <div class="d-flex">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <a href="#" class="ms-auto text-white text-decoration-none text-opacity-50">Forgot password?</a>
                                </div>
                                <input type="password" class="form-control form-control-lg bg-white bg-opacity-5" value=""
                                    placeholder="" name="password" require />
                            </div>
                          
                            <button type="submit" class="mb-5 btn btn-outline-theme btn-lg d-block w-100 fw-500 mb-3">Sign In</button>
                           
                        </form>
                    </div>
                </div>
                
            </div>
          </div>
          
      </div>
      <!-- END login-content -->
  </div>    
    @endsection