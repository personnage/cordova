<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Laravel</title>

  <link rel="stylesheet" href="/css/app.css">
  <link rel="stylesheet" href="/vendor/bower_components/font-awesome/css/font-awesome.css">
</head>
<body>
  <div class="container position-ref">
    <div class="row">

      <div class="col-sm-6 flex-center full-height">
        <p class="title">Welcome</p>
      </div>

      <div class="col-sm-6 flex-center full-height">
        <div class="panel panel-default" style="width: 100%;">
          <div class="panel-heading">Sign In</div>
          <div class="panel-body">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}" autocomplete="off">
              {{ csrf_field() }}

              <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                <label for="username" class="col-sm-2 control-label">Username</label>
                <div class="col-sm-8">
                  <input type="text" name="username" class="form-control" id="username" value="{{ old('username') }}" autocomplete="off">

                  @if ($errors->has('username'))
                  <span class="help-block">
                    <strong>{{ $errors->first('username') }}</strong>
                  </span>
                  @endif

                </div>
              </div>
              <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="col-sm-2 control-label">Password</label>
                <div class="col-sm-8">
                  <input type="password" name="password" class="form-control" id="password" autocomplete="off">

                  @if ($errors->has('password'))
                  <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                  </span>
                  @endif
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="remember"> Remember Me
                    </label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                  <button type="submit" class="btn btn-default">
                    Login
                  </button>

                  <a class="btn btn-link" href="{{ url('/password/reset') }}">
                    Forgot Your Password?
                  </a>

                  <a class="btn btn-link" href="{{ url('register') }}">No Account?</a>
                </div>
              </div>

              <div class="col-sm-10 col-sm-offset-2">
                <hr>
                <div class="btn-group" role="group" aria-label="...">
                  <a href="{{ url('auth/github') }}" class="btn btn-default">
                    <i class="fa fa-github" aria-hidden="true"></i>
                  </a>
                  <a href="{{ url('auth/google') }}" class="btn btn-danger">
                    <i class="fa fa-google-plus" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
            </form>
          </div>
        </div> {{-- end panel --}}
      </div> {{-- end col --}}
    </div> {{-- end row --}}
  </div>
</body>
</html>
