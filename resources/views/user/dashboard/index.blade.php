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
      const [data,setData]=React.useState({
        'data':[],
        'data-count':0,
        'max-page':0,
      });
      const [page,setPage]=React.useState(1);
      const [Loading,setLoading]=React.useState();
      const [Screen,setScreen]=React.useState({
        'screen':'MainScreen',
        'args':[]
      });
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
          let data=await fetch("<?=url('user/home/readPage');?>/"+pg);
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
      function CardComponent(props){
        return (
          <div className='col-10 col-sm-10 col-md-5 col-lg-3 col-xl-3 m-2'>
            <div className="card">
              <div className="card-header">
                {props.title}
              </div>
              <div className="card-body">
                <p className="card-text">
                  <img src={props.img} width="100" height="100"/>
                </p>
              </div>
              <div className="card-footer">
                <p>Rp. {props.harga}</p>
                <button type="button" onClick={()=>{setScreen((nilaiLama)=>{
                  return {
                    ...nilaiLama,
                    ['screen']:'ModalPesan',
                    ['args']:[props.id_barang]
                  }
                });}} className="btn btn-primary">PESAN</button>
              </div>
            </div>
          </div>
        )
      }

      function ModalPesan(){
        let [input,setInput]=React.useState({
          'id_barang':'',
          'jumlah':0,
          'gambar':"",
        });
        let [LoadingForm,setLoadingForm]=React.useState();
        async function pesanBarang(e){
          e.preventDefault();
          let fr=new FormData();
          fr.append('jumlah',input['jumlah']);
          fr.append('id_barang',input['id_barang']);
          fr.append('_token',"<?=csrf_token()?>");
          setLoadingForm(true);
          const response=await fetch("<?=url('user/home/pesan_barang')?>",{
            method:'POST',
            body:fr,
          });
          if(response.status=='200'){
            setInput((nilaiLama)=>{
              return {
                ...nilaiLama,
                ['jumlah']:'0'
              }
            });
            alert('PESANAN SUDAH DIKIRIM');
            readPage(page);
            setScreen((nilaiLama)=>{
              return {
                ...nilaiLama,
                ['screen']:'MainScreen',
                ['args']:[]
              }
            })
          }
          else{
            let pesan=await response.json();
            pesan=Object.values(pesan);
            pesan=pesan.map((p)=>p[0]+"\n");
            alert(pesan);
          }
          setLoadingForm(false);
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
        async function readOne(id_barang){
          setLoadingForm(true);
          let res=await fetch("<?=url('user/home/');?>/"+id_barang);
          if(res.status==200){
            let data=await res.json();
            setInput((nilaiLama)=>{
              return {
                ...nilaiLama,
                ['id_barang']:id_barang,
                ['gambar']:data.data[0].gambar
              }
            });
          }
          setLoadingForm(false);
        }

        React.useEffect(()=>{
          readOne(Screen.args[0]);
        },[]);
        return (
          <div className="row">
            <div className='container col-sm-12 col-md-12 col-lg-5 col-xl-5'>
              {LoadingForm ?
              <p>LOADING</p>
              :
              <form onSubmit={pesanBarang}>
                <div className="card">
                  <div className='card-header'>
                    <h5 className="card-title">PESAN BARANG</h5>
                    <input type="button" value='close' class='btn btn-danger' onClick={()=>{setScreen((nilaiLama)=>{
                      return {
                        ...nilaiLama,
                        ['screen']:'MainScreen',
                        ['args']:[]
                      }
                    });}}/>
                  </div>
                  <div className="card-body">
                    <img src={input["gambar"]} width="200" height="200"/><br/>
                    <TextInput onChange={inputHandler} value={input['jumlah']} label='JUMLAH' name='jumlah' type='number'/>
                  </div>
                  <div className="card-footer">
                    <button type="submit" className="btn btn-primary">Kirim Pesanan</button>
                  </div>
                </div>
              </form>
              }
            </div>
          </div>
          )
      }
      function MainScreen(){
        return (
          <div className={'row'}>
          <div className={'col-sm-10 col-md-10 col-lg-10 col-xl-10 container'}>
            <div className='row'>
              {data.data.map((dt,index)=>
                <CardComponent key={index} id_barang={dt.id_barang} title={dt.nama_barang} img={dt.gambar} harga={dt.harga} link="#" />
              )}
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
        );
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
          {Screen.screen=='MainScreen' && <MainScreen/>}
          {Screen.screen=='ModalPesan' && <ModalPesan/>}
        </div>
      )
    }
  </script>
  @include('ReactComponents.Root')
</body>
</html>