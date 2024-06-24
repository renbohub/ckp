  
    @extends('layouts.guest')

    @section('title', 'AJI MIS | DASHBOARD')
    
    @section('content')
    <div class="auth-box h-full flex flex-col justify-center">
        <div class="mobile-logo text-center mb-6 lg:hidden flex justify-center">
            <div class="mb-10 inline-flex items-center justify-center">
                
                <span class="ltr:ml-3 rtl:mr-3 text-xl font-Inter font-bold text-slate-900 dark:text-white">DashCode</span>
            </div>
        </div>
        <div class="flex-col sm:flex items-center justify-center relative py-8 sm:py-10 lg:py-0">
            <div class="w-full px-4 sms:px-0 sm:w-[450px]">
                <div class="text-center">
                    <h4 class="font-medium">
                        contact your administrator to active your account
                    </h4>
                    <p>If you have click link verification <br>
                        <a href="{{route('login-main')}}" style="color: blue">click here</a> to login
                    </p>                 
                   @if(isset($code) && !empty($code))
                        <!-- Your HTML or Blade code when $variable is set and not empty -->
                        {{ $code }} <!-- Display the variable, if needed -->
                    @else
                        
                   
                    
                </div>
            </div>
        </div>
    </div>
@endsection