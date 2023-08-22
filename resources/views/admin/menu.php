
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="<?=url('admin/home');?>">Navbar</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent" style="margin-right:400px">
        <ul class="navbar-nav mb-2 mb-lg-0  ms-auto">
          <li class="nav-item">
            <a class="nav-link <?=$activeMenu=='Home' ? "active" : ""?>" aria-current="page" href="<?=url('admin/home');?>">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?=$activeMenu=='Barang' ? "active" : ""?>" href="<?=url('admin/barang');?>">Barang</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?=$activeMenu=='Penjualan' ? "active" : ""?>" href="<?=url('admin/penjualan');?>">Penjualan</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?=$activeMenu=='User' ? "active" : ""?>" href="<?=url('admin/user');?>">User</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?=url('logout');?>">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>