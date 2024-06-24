    
    @extends('layouts.guest')

    @section('title', 'AJI MIS | DASHBOARD')
    
    @section('content')
    
    <div class="loginwrapper bg-cover bg-no-repeat bg-center" style="background-image: url(assets/images/all-img/page-bg.png);">
        <div class="lg-inner-column">
          <div class="left-columns lg:w-1/2 lg:block hidden">
            <div class="logo-box-3">
              <a heref="index.html" class="">
                <img src="assets/images/logo/logo-white.svg" alt="">
              </a>
            </div>
          </div>
          <div class="lg:w-1/2 w-full flex flex-col items-center justify-center">
            <div class="auth-box-3">
              <div class="mobile-logo text-center mb-6 lg:hidden block">
                <a heref="index.html">
                  <img src="assets/images/logo/logo.svg" alt="" class="mb-10 dark_logo">
                  <img src="assets/images/logo/logo-white.svg" alt="" class="mb-10 white_logo">
                </a>
              </div>
              <div class="text-center 2xl:mb-10 mb-5">
                 <h4 class="font-medium text-center"><center><img src="{{asset('public/sites/img/logo-dharma.png')}}" width="50%" alt=""></center></h4>
                <div class="text-slate-500 dark:text-slate-400 text-base">
                  Create Account
                </div>
              </div>
    
              <!-- BEGIN: Registration Form -->
              <form class="space-y-4" action="{{route('signup-action')}}" method="POST">
                @csrf
                <div class="fromGroup">
                  <label class="block capitalize form-label">Username</label>
                  <div class="relative ">
                    <input type="text" name="username" class="  form-control py-2" placeholder="Enter your name" required>
                  </div>
                </div>
                <div class="fromGroup">
                    <label class="block capitalize form-label">Phone Number</label>
                    <div class="relative ">
                      <input type="tel" name="number" class="  form-control py-2" placeholder="Enter your number" required>
                    </div>
                  </div>
                <div class="fromGroup">
                  <label class="block capitalize form-label">email</label>
                  <div class="relative ">
                    <input type="email" name="email" class="  form-control py-2" placeholder="Enter your email" required>
                  </div>
                </div>
                <div class="fromGroup       ">
                  <label class="block capitalize form-label  ">passwrod</label>
                  <div class="relative "><input type="password" name="password" class="  form-control py-2   " placeholder="Enter your password" required>
                  </div>
                </div>
                <div class="flex justify-between">
                  <label class="flex items-center cursor-pointer">
                    <input type="checkbox" class="hiddens" required>
                    <span class="text-slate-500 dark:text-slate-400 text-sm leading-6 capitalize">You accept our Terms and Conditions and
                    Privacy Policy</span>
                  </label>
                </div>
                <button class="btn btn-dark block w-full text-center">Create an account</button>
              </form>
              <!-- END: Registration Form -->
              <div style="color: red">
                <center>
                  <br>
                  
                </center>
              </div>
              <div class="max-w-[242px] mx-auto mt-8 w-full">
    
                <!-- BEGIN: Social Log in Area -->
               
                <!-- END: Social Log In Area -->
              </div>
              <div class="mx-auto font-normal text-slate-500 dark:text-slate-400 2xl:mt-12 mt-6 uppercase text-sm text-center">
                Already registered?
                <a href="{{route('login-main')}}" class="text-slate-900 dark:text-white font-medium hover:underline">
                  Sign In
                </a>
              </div>
            </div>
          </div>
          <div class="auth-footer3 text-white py-5 px-5 text-xl w-full">
            
          </div>
        </div>
      </div>

@endsection