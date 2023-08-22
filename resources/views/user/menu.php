<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="<?=url('user/home');?>">Navbar</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent" style="margin-right:400px">
        <ul class="navbar-nav mb-2 mb-lg-0  ms-auto">
          <li class="nav-item">
            <a class="nav-link <?=$activeMenu=='Home' ? "active" : ""?>" aria-current="page" href="<?=url('user/home');?>">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?=$activeMenu=='Riwayat' ? "active" : ""?>" href="<?=url('user/riwayat_pembelian');?>">Riwayat Pembelian</a>
          </li>
          <li class="nav-item dropdown <?=$activeMenu=='DataSaya' ? "active" : ""?>">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Data Saya
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="<?=url('user/ubah_nama');?>">Ubah Nama</a></li>
              <li><a class="dropdown-item" href="<?=url('user/ubah_password');?>">Ubah Password</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?=url('logout');?>">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>