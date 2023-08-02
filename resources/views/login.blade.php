<!doctype html>
<html lang="en">

<head>
  <title>Register User</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    {{-- jquery Cdn --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h1 class="text-center mt-5">Log In</h1>
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


        <form>
            
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" class="form-control">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="text" id="password" name="password" class="form-control">
        </div>
        <button class="btn btn-primary mt-3" id="submit">Submit</button>
    </form>
    <a href="{{route('register')}}" style="margin-left: 40%;" class="text-center text-decoration-none">Register Here....!</a>


            </div>
        </div>
        
    </div>

    <script>

        $('#submit').click(function (event){
            event.preventDefault();
          
            var email = $('#email').val();
            var password = $('#password').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
            type: "POST",
            url: 'http://localhost:8000/api/login',
            data: {
                email:email,
                password:password
            },
            success:function (resp){
               
                if(resp.success == true){

                var accessToken = resp.token;
                // Store the access token in the browser's local storage
                localStorage.setItem('access_token', accessToken);
            
                window.location.href = '/dashboard';

                    $('#flash-message').text(resp.message).fadeIn();
                }else{
                    $('#flash-message').text(resp.message).fadeIn();
                }
                
            },
            error:function (err){
                console.log(err);
            }
        });
    });
        
    </script>
  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>

</html>