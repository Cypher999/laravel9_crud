<!DOCTYPE html>
<html lang="en">
<head>
  @include('partials.head')
</head>
<body>
  <div id='root'>

  </div>
  @include('partials/js')
  @include('ReactComponents.TextInput')
  <script type="text/babel">
    function FormContainer(){
      const formStyle={
        position:'relative',
        border:'1px solid black',
        borderRadius:'20px',
        padding:'20px'
      }
      return(
        <form style={formStyle} method='post' action="{{url('login')}}">
          <input type="hidden" name="_token" value="{{csrf_token()}}"/>
          <h2>LOGIN</h2>
          <TextInput type="text" name="username" label="USERNAME"/>
          <TextInput type="password" name="password" label="PASSWORD"/>
          <input type="submit" className="btn btn-primary col-12 mb-4" value="LOGIN"/>
          <a href="{{url('signup')}}">
            <input type="button" className="btn btn-success col-12" value="SIGN UP"/>
          </a>
        </form>
      )
    }
    function App(){
      const containerStyle={
        marginTop:'150px',
      }
      return(
        <div className={'row'}>
          <div className={'col-sm-10 col-md-8 col-lg-4 col-xl-4 container'} style={containerStyle}>
            <?php if(count($errors)>0){?>
              <div className="alert alert-danger">
                <ul>
                <?php foreach($errors->all() as $e){
                ?>
                  <li><?=$e?></li>
                <?php }?>
                </ul>
              </div>
            <?php } if(Session::get('customErrors')!=NULL){?>
              <div className="alert alert-danger">
                <ul>
                <?php foreach(Session::get('customErrors') as $e){
                ?>
                  <li><?=$e?></li>
                <?php }?>
                </ul>
              </div>
            <?php }?>
            <FormContainer/>
          </div>
        </div>
      )
    }
  </script>
  @include('ReactComponents.Root')
</body>
</html>