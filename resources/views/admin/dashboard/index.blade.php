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
      const [nilai,setNilai]=React.useState({
        'data-barang':0,
        'data-penjualan':0,
        'data-user':0
      });
      async function readAllData(){
        try {
          let data=await fetch("<?=url('admin/home/readAllData');?>");
          data=await data.json();
          setNilai(function(nilaiLama){
            return {
              ...nilaiLama,
              ['data-barang']:data.jumlahBarang,
              ['data-penjualan']:data.jumlahPenjualan,
              ['data-user']:data.jumlahUser
            }
          });
        } catch (error) {
          console.log(error);
        }
      }
      React.useEffect(function(){
        readAllData();

      },[]);

      function CardComponent(props){
        return (
          <div className='col-sm-12 col-md-12 col-lg-5 col-xl-5 m-2'>
            <div className="card">
              <div className="card-body">
                <h5 className="card-title">{props.title}</h5>
                <p className="card-text">{props.subtitle}</p>
                <a href={props.link} className="btn btn-primary">LIHAT DATA</a>
              </div>
            </div>
          </div>
        )
      }

      return(
        <div className={'row'}>
          <div className={'col-sm-10 col-md-10 col-lg-10 col-xl-10 container'}>
            <div className='row'>
              <CardComponent title="DATA BARANG" subtitle={nilai['data-barang']} link="{{url('admin/barang')}}"/>
              <CardComponent title="DATA PENJUALAN" subtitle={nilai['data-penjualan']} link="{{url('admin/penjualan')}}"/>
              <CardComponent title="DATA USER" subtitle={nilai['data-user']} link="{{url('admin/user')}}"/>
            </div>
          </div>
        </div>
      )
    }
  </script>
  @include('ReactComponents.Root')
</body>
</html>