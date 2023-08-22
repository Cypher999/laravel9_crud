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
  <script type="text/babel">
    function App(){
      const [data,setData]=React.useState({
        'data':[],
        'data-count':0,
        'max-page':0,
      });
      const [page,setPage]=React.useState(1);
      const [Screen,setScreen]=React.useState({screen:'MainScreen',args:[]});
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
          let data=await fetch("<?=url('admin/barang/readPage');?>/"+pg);
          data=await data.json();
          setLoading(false);
          setData(function(nilaiLama){
            return {
              ...nilaiLama,
              ['data']:data.data,
              ['data-count']:data['data-count'],
              ['max-page']:data['max-page'],
            }
          });
        } catch (error) {
          console.log(error);
        }
      }


      async function addData(e,data){
        e.preventDefault();

        let fr=new FormData();
        fr.append('nama_barang',data['nama_barang']);
        fr.append('harga',data['harga']);
        fr.append('stok',data['stok']);
        fr.append('file',data['file']);
        fr.append('_token',"<?=csrf_token()?>");

        const response=await fetch("<?=url('admin/barang')?>",{
          method:'POST',
          body:fr,
        });

        if(response.status=='200'){
          alert('DATA SUDAH DISIMPAN');

          setPage(1);
          readPage(page);

          setScreen((dataLama)=>{
            return {
              ...dataLama,
              ['screen']:'MainScreen'
            }
          })
        }
        else{
          let pesan=await response.json();
          pesan=Object.values(pesan);
          pesan=pesan.map((p)=>p[0]+"\n");
          alert(pesan);
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
            <td>{props.data[2]}</td>
            <td>
              <img src={props.data[3]} width="100" height="100"/>
            </td>
            <td>
              <input type="button" onClick={()=>{
                setScreen((dataLama)=>{
                  return {
                    ...dataLama,
                    ['screen']:'Edit',
                    ['args']:[props.data[4]]
                  }
                });

              }} class="btn btn-warning m-1" value="Edit"/>
              <input type="button" onClick={async()=>{
                  let hapus=confirm('HAPUS DATA INI ?');
                  if(hapus){
                    await Delete(props.data[4]);
                  }

                }
              } class="btn btn-danger m-1" value="Hapus"/>
            </td>

          </tr>
        )
      }
      async function Delete(id_barang){

          let fr=new FormData();
          fr.append('_method',"delete");
          fr.append('_token',"<?=csrf_token();?>");

          const response=await fetch("<?=url('admin/barang')?>/"+id_barang,{
            method:'POST',
            body:fr,
          });
          if(response.status=='200'){
            alert('DATA SUDAH DIHAPUS');

            readPage(1);
            setPage(1);
            setScreen((dataLama)=>{
            return {
              ...dataLama,
              ['screen']:'MainScreen'
            }
          })
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
          'nama_barang':"",
          'harga':0,
          "stok":0,
          "file":"",
          "preview":""
        });

        function inputHandler(e){
          setInput((nilaiLama)=>{
            if(e.target.type!='file'){
              return {...nilaiLama,[e.target.name]:e.target.value}
            }
            else{
              return {
                ...nilaiLama,
                [e.target.name]:e.target.files[0],
                ['preview']:URL.createObjectURL(e.target.files[0])
              }
            }
          });
        }

        return (
          <div className="row">
            <div className='container col-sm-12 col-md-12 col-lg-5 col-xl-5'>
              <form onSubmit={(e)=>{addData(e,input)}}>
                <div className="card">
                  <div className='card-header'>
                    <h5 className="card-title">ADD BARANG</h5>
                    <input type="button" value='close' class='btn btn-danger' onClick={()=>{setScreen((dataLama)=>{
            return {
              ...dataLama,
              ['screen']:'MainScreen'
            }
          })}}/>
                  </div>
                  <div className="card-body">
                      <TextInput onChange={inputHandler} value={input['nama-barang']} label='NAMA BARANG' name='nama_barang' type='text'/>
                      <TextInput onChange={inputHandler} value={input['harga']} label='HARGA' name='harga' type='number'/>
                      <TextInput onChange={inputHandler} value={input['stok']} label='STOK' name='stok' type='number'/>
                      <img src={input["preview"]} width="200" height="200"/>
                      <input type="file" name="file" onChange={inputHandler}/>

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
      function ModalEdit(){
        let [input,setInput]=React.useState({
          'id_barang':"",
          'nama_barang':"",
          'harga':0,
          "stok":0,
          "file":"",
          "preview":""
        });
        let [LoadingForm,setLoadingForm]=React.useState(true);
        async function readOne(id_barang){
          let res=await fetch("<?=url('admin/barang');?>/"+id_barang);
          if(res.status==200){
            let data=await res.json();
            setInput((nilaiLama)=>{
              return {
                ...nilaiLama,
                ['id_barang']:id_barang,
                ['nama_barang']:data.data[0].nama_barang,
                ['harga']:data.data[0].harga,
                ['stok']:data.data[0].stok,
                ['preview']:data.data[0].gambar,
              }
            });
            setLoadingForm(false);

          }
        }
        React.useEffect(()=>{
          readOne(Screen.args[0]);
        },[]);
        function inputHandler(e){
          setInput((nilaiLama)=>{
            if(e.target.type!='file'){
              return {...nilaiLama,[e.target.name]:e.target.value}
            }
            else{
              return {
                ...nilaiLama,
                [e.target.name]:e.target.files[0],
                ['preview']:URL.createObjectURL(e.target.files[0])
              }
            }
          });
        }
        async function editData(e,input){
          setLoadingForm(true);
          e.preventDefault();
          let fr=new FormData();
          fr.append('nama_barang',input['nama_barang']);
          fr.append('harga',input['harga']);
          fr.append('stok',input['stok']);
          fr.append('_method','PUT');
          if(input["file"]!=""){
            fr.append('file',input['file']);
          }

          const response=await fetch("<?=url('admin/barang')?>/"+input['id_barang'],{
            method:'POST',
            headers:{
              'X-CSRF-TOKEN':"<?=csrf_token()?>"
            },
            body:fr,
          });
          if(response.status=='200'){
            alert('DATA SUDAH DISIMPAN');

            readPage(page);
            setScreen((dataLama)=>{
            return {
              ...dataLama,
              ['screen']:'MainScreen'
              }
            })
          }
          else{
            let pesan=await response.json();
            pesan=Object.values(pesan);
            pesan=pesan.map((p)=>p[0]+"\n");
            alert(pesan);
          }
        }
          return (
            <div>
            {LoadingForm ?
          <div className="row">
            <div className='container col-sm-12 col-md-12 col-lg-5 col-xl-5'>
              Loading...
            </div>
          </div>
          :
          <div className="row">
            <div className='container col-sm-12 col-md-12 col-lg-5 col-xl-5'>
              <form onSubmit={(e)=>{editData(e,input)}}>
                <div className="card">
                  <div className='card-header'>
                    <h5 className="card-title">EDIT BARANG</h5>
                    <input type="button" value='close' class='btn btn-danger' onClick={()=>{
                      setScreen((dataLama)=>{
                      return {
                        ...dataLama,
                        ['screen']:'MainScreen'
                      }
                    })}}/>
                  </div>
                  <div className="card-body">
                    <TextInput onChange={inputHandler} value={input['nama_barang']} label='NAMA BARANG' name='nama_barang' type='text'/>
                    <TextInput onChange={inputHandler} value={input['harga']} label='HARGA' name='harga' type='number'/>
                    <TextInput onChange={inputHandler} value={input['stok']} label='STOK' name='stok' type='number'/>
                    <img src={input["preview"]} width="200" height="200"/>
                    <input type="file" name="file" onChange={inputHandler}/>

                  </div>
                  <div className="card-footer">
                    <button type="submit" className="btn btn-primary">Save</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          }
          </div>
          )

      }

      function MainScreen(){
        return (
          <div className={'row'}>
            <div className={'col-sm-10 col-md-10 col-lg-10 col-xl-10 container'}>
              <button type="button" onClick={()=>{setScreen((dataLama)=>{
            return {
              ...dataLama,
              ['screen']:'Add'
            }
          })}} className="btn btn-primary">
                ADD DATA
              </button>
              <div class="table-bordered table-responsive">
                <table className='table '>
                  <thead>
                    <tr>
                      <td>NAMA BARANG</td>
                      <td>HARGA</td>
                      <td>STOK</td>
                      <td>GAMBAR</td>
                      <td>KONTROL</td>
                    </tr>
                  </thead>
                  <tbody>
                    {data.data.map((dt,index)=>{
                      return(
                        <TableItem key={index} data={[dt.nama_barang,dt.harga,dt.stok,dt.gambar,dt.id_barang]}/>
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
          {Screen.screen== 'MainScreen' && <MainScreen/>}
          {Screen.screen== 'Add' && <ModalAdd/>}
          {Screen.screen== 'Edit' && <ModalEdit/>}
        </div>
      )
    }
  </script>
  @include('ReactComponents.Root')
</body>
</html>