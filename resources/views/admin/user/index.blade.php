<!DOCTYPE html>
<html lang="en">
<head>
  @include('partials.head')
</head>
<body>
  @include('admin.menu')
  <div id='root'>

  </div>
  @include('partials.js')
  @include('ReactComponents.TextInput')
  @include('ReactComponents.SelectInput')
  <script type="text/babel">
    function App(){
      const [data,setData]=React.useState({
        'data':[],
        'data-count':0,
        'max-page':0,
      });
      const [page,setPage]=React.useState(1);
      const [Screen,setScreen]=React.useState('MainScreen');
      const [id_user,setIdUser]=React.useState("");
      const [Loading,setLoading]=React.useState(true);
      let minPage=0;
      if(page<3){
        minPage=1;
      }
      else{
        minPage=page-2;
      }
      if(minPage<=0){
        minPage=1;
      }
      let maxPage=0;
      if(page<3){
        maxPage=5;
      }
      else{
        maxPage=page+2;
      }
      if(maxPage>data['max-page']){
        maxPage=data['max-page'];
      }

      function buttonPrev(){
        let halBaru=page-1
        setPage((nilaiLama)=>halBaru);
        if(halBaru==0){
          halBaru=1;
          setPage((nilaiLama)=>1);
        }
        readPage(halBaru);
      }
      function buttonNext(){
        let halBaru=page+1
        setPage((nilaiLama)=>halBaru);
        if(halBaru>data['max-page']){
          halBaru=data['max-page'];
          setPage((nilaiLama)=>data['max-page']);
        }
        readPage(halBaru);
      }

      let pageButton=[];
      for(let i=minPage;i<=maxPage;i++){
        if(i==page){
          pageButton.push(
            <input type="button" key={i} className="btn btn-outline-secondary" value={i}/>
          )
        }
        else{
          pageButton.push(
            <input type="button" onClick={()=>{setPage(()=>i);readPage(i);}} key={i} className="btn btn-secondary" value={i}/>
          )
        }
      }

      async function readPage(pg){
        try {
          setLoading(true);
          let data=await fetch("<?=url('admin/user/readPage');?>/"+pg);
          data=await data.json();
          setData(function(nilaiLama){
            return {
              ...nilaiLama,
              ['data']:data.data,
              ['data-count']:data['data-count'],
              ['max-page']:data['max-page'],
            }
          });
          setLoading(false);
        } catch (error) {
          console.log(error);
        }
      }

      React.useEffect(function(){
        readPage(page);
      },[]);
      function TableItem(props){
        return(
          <tr>
            <td>{props.data[0]}</td>
            <td>{props.data[1]}</td>
            <td>
              <input type="button" onClick={()=>{setScreen('EditNama');setIdUser(props.data[2])}} class="btn btn-warning m-1" value="Edit Data User"/>
              <input type="button" onClick={()=>{setScreen('EditPassword');setIdUser(props.data[2])}} class="btn btn-warning m-1" value="Edit Password"/>
              <input type="button" onClick={async()=>{
                  let hapus=confirm('HAPUS DATA INI ?');
                  if(hapus){
                    await Delete(props.data[2]);
                  }

                }
              } class="btn btn-danger m-1" value="Hapus"/>
            </td>

          </tr>
        )
      }
      async function Delete(id_user){
          let fr=new FormData();
          fr.append('_method',"delete");
          fr.append('_token',"<?=csrf_token();?>");
          const response=await fetch("<?=url('admin/user')?>/"+id_user,{
            method:'POST',
            body:fr,
          });
          if(response.status=='200'){
            alert('DATA SUDAH DIHAPUS');
            readPage(1);
            setPage(1);
            setScreen('MainScreen')
          }
          else{
            let pesan=await response.json();
            pesan=Object.values(pesan);
            pesan=pesan.map((p)=>p[0]+"\n");
            alert(pesan);
          }
        }
      function ModalAdd(){
        let [input,setInput]=React.useState({
          'username':"",
          'password':"",
          "konfirmasi":"",
          "type":"U"
        });
        async function addData(e){
          e.preventDefault();
          let fr=new FormData();
          fr.append('username',input['username']);
          fr.append('password',input['password']);
          fr.append('konfirmasi',input['konfirmasi']);
          fr.append('type',input['type']);
          fr.append('_token',"<?=csrf_token()?>");
          const response=await fetch("<?=url('admin/user')?>",{
            method:'POST',
            body:fr,
          });
          if(response.status=='200'){
            setInput((nilaiLama)=>{
              return {
                ...nilaiLama,
                ['username']:'',
                ['password']:"",
                ['konfirmasi']:"",
                ['type']:"U"
              }
            });
            alert('DATA SUDAH DISIMPAN');
            readPage(data['max-page']);
            setPage(data['max-page']);
            setScreen('MainScreen')
          }
          else{
            let pesan=await response.json();
            pesan=Object.values(pesan);
            pesan=pesan.map((p)=>p[0]+"\n");
            alert(pesan);
          }
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
              <form onSubmit={addData}>
                <div className="card">
                  <div className='card-header'>
                    <h5 className="card-title">ADD USER</h5>
                    <input type="button" value='close' class='btn btn-danger' onClick={()=>{setScreen('MainScreen');}}/>
                  </div>
                  <div className="card-body">
                    <TextInput onChange={inputHandler} value={input['username']} label='USERNAME' name='username' type='text'/>
                    <TextInput onChange={inputHandler} value={input['password']} label='PASSWORD' name='password' type='text'/>
                    <TextInput onChange={inputHandler} value={input['konfirmasi']} label='KONFIRMASI PASSWORD' name='konfirmasi' type='text'/>
                    <SelectInput onChange={inputHandler} value={input['type']} label='TIPE USER' name='type'>
                      <option value="A">Admin</option>
                      <option value="U">User Biasa</option>
                    </SelectInput>/>
                  </div>
                  <div className="card-footer">
                    <button type="submit" className="btn btn-primary">Save</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          )
      }

      function ModalEditNama(){
        let [input,setInput]=React.useState({
          'username':"",
          "type":"U"
        });
        let [LoadingForm,setLoadingForm]=React.useState(true);
        async function editData(e){
          e.preventDefault();
          let fr=new FormData();
          fr.append('username',input['username']);
          fr.append('_method','PUT');
          fr.append('type',input['type']);
          const response=await fetch("<?=url('admin/user/updateData')?>/"+id_user,{
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
            readPage(page);
            setScreen('MainScreen')
          }
          else{
            let pesan=await response.json();
            pesan=Object.values(pesan);
            pesan=pesan.map((p)=>p[0]+"\n");
            alert(pesan);
          }
        }

        async function readOne(id_user){
          let res=await fetch("<?=url('admin/user');?>/"+id_user);
          if(res.status==200){
            let data=await res.json();
            setInput((nilaiLama)=>{
              return {
                ...nilaiLama,
                ['username']:data.data[0].username
              }
            });
            setLoadingForm(false);
          }
        }

        React.useEffect(()=>{
          readOne(id_user);
        },[]);
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
            {LoadingForm
              ?
              <div className='container col-sm-12 col-md-12 col-lg-5 col-xl-5'>
                LOADING
              </div>
              :
              <div className='container col-sm-12 col-md-12 col-lg-5 col-xl-5'>
                <form onSubmit={editData}>
                  <div className="card">
                    <div className='card-header'>
                      <h5 className="card-title">EDIT DATA USER</h5>
                      <input type="button" value='close' class='btn btn-danger' onClick={()=>{setScreen('MainScreen');}}/>
                    </div>
                    <div className="card-body">
                      <TextInput onChange={inputHandler} value={input['username']} label='USERNAME' name='username' type='text'/>
                      <SelectInput onChange={inputHandler} value={input['type']} label='TIPE USER' name='type'>
                        <option value="A">Admin</option>
                        <option value="U">User Biasa</option>
                      </SelectInput>/>
                    </div>
                    <div className="card-footer">
                      <button type="submit" className="btn btn-primary">Save</button>
                    </div>
                  </div>
                </form>
              </div>
            }
          </div>
          )
      }

      function ModalEditPassword(){
        let [input,setInput]=React.useState({
          'password':"",
          "konfirmasi":""
        });
        async function editData(e){
          e.preventDefault();
          let fr=new FormData();
          fr.append('password',input['password']);
          fr.append('konfirmasi',input['konfirmasi']);
          fr.append('_method','PUT');
          const response=await fetch("<?=url('admin/user/updatePassword')?>/"+id_user,{
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
                ['password']:"",
                ['konfirmasi']:""
              }
            });
            alert('DATA SUDAH DIUPDATE');
            readPage(page);
            setScreen('MainScreen')
          }
          else{
            let pesan=await response.json();
            pesan=Object.values(pesan);
            pesan=pesan.map((p)=>p[0]+"\n");
            alert(pesan);
          }
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
              <form onSubmit={editData}>
                <div className="card">
                  <div className='card-header'>
                    <h5 className="card-title">EDIT PASSWORD</h5>
                    <input type="button" value='close' class='btn btn-danger' onClick={()=>{setScreen('MainScreen');}}/>
                  </div>
                  <div className="card-body">
                    <TextInput onChange={inputHandler} value={input['password']} label='PASSWORD' name='password' type='text'/>
                    <TextInput onChange={inputHandler} value={input['konfirmasi']} label='KONFIRMASI PASSWORD' name='konfirmasi' type='text'/>
                  </div>
                  <div className="card-footer">
                    <button type="submit" className="btn btn-primary">Save</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          )
      }

      function MainScreen(){
        return (
          <div className={'row'}>
            <div className={'col-sm-10 col-md-10 col-lg-10 col-xl-10 container'}>
              <button type="button" onClick={()=>{setScreen('Add')}} className="btn btn-primary">
                ADD DATA
              </button>
              <div class="table-bordered table-responsive">
                <table className='table table-bordered table-responsive'>
                  <thead>
                    <tr>
                      <td>USERNAME</td>
                      <td>TYPE</td>
                      <td>KONTROL</td>
                    </tr>
                  </thead>
                  <tbody>
                    {data.data.map((dt,index)=>{
                      return(
                        <TableItem key={index} data={[dt.username,dt.type,dt.id_user]}/>
                      )
                    })}
                  </tbody>
                </table>
              </div>
              <div className='row'>
                <div className={'col-sm-12 col-md-12 col-lg-6 col-xl-6'}>
                  <input onClick={buttonPrev} type="button" class="btn btn-secondary" value="PREV"/>
                  {pageButton}
                  <input onClick={buttonNext} type="button" class="btn btn-secondary" value="NEXT"/>
                </div>
              </div>
            </div>
          </div>
        )
      }
      function LoadingElements(){
        const LoadingStyle={
          position: 'fixed',
          top: '0px',
          left: '0px',
          background: '#08ff005e',
          width: '100%',
          height: '100%',
          zIndex: '100',
        }
        const LoadingHeaderStyle={
          position: 'absolute',
          top: '40px',
          left: '40px',
          background: 'green',
          padding: '10px',
          borderRadius: '20px',
        }
        return (
          <div style={LoadingStyle}>
            <h2 style={LoadingHeaderStyle}>Loading</h2>
          </div>
        )
      }
      return(
        <div>
          {Loading && <LoadingElements/>}
          {Screen== 'MainScreen' && <MainScreen/>}
          {Screen== 'Add' && <ModalAdd/>}
          {Screen== 'EditNama' && <ModalEditNama/>}
          {Screen== 'EditPassword' && <ModalEditPassword/>}
        </div>
      )
    }
  </script>
  @include('ReactComponents.Root')
</body>
</html>