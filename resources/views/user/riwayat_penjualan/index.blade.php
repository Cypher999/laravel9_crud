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
  <script type="text/babel">
    function App(){
      const [data,setData]=React.useState({
        'data':[],
        'data-count':0,
        'max-page':0,
      });
      const [page,setPage]=React.useState(1);
      const [Screen,setScreen]=React.useState('MainScreen');
      const [id_penjualan,setIdUser]=React.useState("");
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
          let data=await fetch("<?=url('user/riwayat_pembelian/readPage');?>/"+pg);
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
            <td>{props.data[2]}</td>
            <td>{props.data[3]}</td>
            <td>
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
      async function Delete(id_penjualan){
          let fr=new FormData();
          fr.append('_method',"delete");
          fr.append('_token',"<?=csrf_token();?>");
          const response=await fetch("<?=url('user/riwayat_pembelian')?>/"+id_penjualan,{
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

      function MainScreen(){
        return (
          <div className={'row'}>
            <div className={'col-sm-10 col-md-10 col-lg-10 col-xl-10 container'}>
              <div class="table-bordered table-responsive">
                <table className='table table-bordered'>
                  <thead>
                    <tr>
                      <td>NAMA BARANG</td>
                      <td>NAMA PEMBELI</td>
                      <td>JUMLAH</td>
                      <td>TANGGAL</td>
                      <td>KONTROL</td>
                    </tr>
                  </thead>
                  <tbody>
                    {data.data.map((dt,index)=>{
                      return(
                        <TableItem key={index} data={[dt.nama_barang,dt.username,dt.jumlah,dt.tgl_penjualan,dt.id_penjualan]}/>
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
        </div>
      )
    }
  </script>
  @include('ReactComponents.Root')
</body>
</html>