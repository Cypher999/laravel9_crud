<!DOCTYPE html>
<html lang="en">
<head>
  @include('partials.head')
</head>
<body>
  @include('user.menu')
  <div id='root'>

  </div>
  @include('partials.js')
  @include('ReactComponents.TextInput')
  <script type="text/babel">
    function App(){

      function MainScreen(){
        let [input,setInput]=React.useState({
          'lama':"",
          'password':"",
          "konfirmasi":""
        });
        let [Loading,setLoading]=React.useState();
        async function editData(e){
          setLoading(true);
          e.preventDefault();
          let fr=new FormData();
          fr.append('lama',input['lama']);
          fr.append('password',input['password']);
          fr.append('konfirmasi',input['konfirmasi']);
          const response=await fetch("<?=url('user/ubah_password')?>",{
            method:'POST',
            headers:{
              'X-CSRF-TOKEN':"<?=csrf_token()?>"
            },
            body:fr,
          });
          if(response.status=='200'){
            setInput((nilaiLama)=>{
              return {
                ...nilaiLama,
                ['lama']:"",
                ['password']:"",
                ['konfirmasi']:""
              }
            });
            alert('DATA SUDAH DIUPDATE');
          }
          else{
            let pesan=await response.json();
            pesan=Object.values(pesan);
            pesan=pesan.map((p)=>p[0]+"\n");
            alert(pesan);
          }
          setLoading(false);
        }
        function inputHandler(e){
          setInput((nilaiLama)=>{
            if(e.target.type!='type'){
              return {...nilaiLama,[e.target.name]:e.target.value}
            }
            else{
              return {
                ...nilaiLama,
                [e.target.name]:e.target.types[0],
                ['preview']:URL.createObjectURL(e.target.types[0])
              }
            }
          });
        }

        return (
          <div className="row">
            <div className='container col-sm-12 col-md-12 col-lg-5 col-xl-5'>
              {
                Loading
                ?
                <p>LOADING</p>
                :
                <form onSubmit={editData}>
                  <div className="card">
                    <div className='card-header'>
                      <h5 className="card-title">EDIT PASSWORD</h5>
                    </div>
                    <div className="card-body">
                      <TextInput onChange={inputHandler} value={input['lama']} label='PASSWORD LAMA' name='lama' type='password'/>
                      <TextInput onChange={inputHandler} value={input['password']} label='PASSWORD BARU' name='password' type='password'/>
                      <TextInput onChange={inputHandler} value={input['konfirmasi']} label='KONFIRMASI PASSWORD' name='konfirmasi' type='password'/>
                    </div>
                    <div className="card-footer">
                      <button type="submit" className="btn btn-primary">Save</button>
                    </div>
                  </div>
                </form>
              }
            </div>
          </div>
          )
      }
      return(
        <MainScreen/>
      )
    }
  </script>
  @include('ReactComponents.Root')
</body>
</html>