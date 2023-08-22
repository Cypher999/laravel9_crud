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
  @include('ReactComponents.SelectInput')
  <script type="text/babel">
    function App(){
      function MainScreen(){
        let [input,setInput]=React.useState({
          'username':"<?=$data[0]['username'];?>"
        });
        let [Loading,setLoading]=React.useState();
        async function editData(e){
          setLoading(true);
          e.preventDefault();
          let fr=new FormData();
          fr.append('username',input['username']);
          fr.append('type',input['type']);
          const response=await fetch("<?=url('user/ubah_nama')?>",{
            method:'POST',
            headers:{
              'X-CSRF-TOKEN':"<?=csrf_token()?>"
            },
            body:fr,
          });
          if(response.status=='200'){
            let data=await response.json();
            setInput((nilaiLama)=>{
              return {
                ...nilaiLama,
                ['username']:""
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
                      <h5 className="card-title">EDIT DATA USER</h5>
                    </div>
                    <div className="card-body">
                      <TextInput onChange={inputHandler} value={input['username']} label='USERNAME' name='username' type='text'/>
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