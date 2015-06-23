<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <h2>Your user has been registered or updated</h2>
 
    <div>
      Your sign up details for: <a href='http://{{"www.".str_replace("www.","",$_SERVER['HTTP_HOST'])}}/admin'>{{$_SERVER['HTTP_HOST']}}</a>  are below:
    </div>
    <div>{{ $email}} </div>
    <div>{{ $username}} </div>
    <div>{{ $name}} </div>
    <div>{{ $password}} </div>
  </body>
</html>