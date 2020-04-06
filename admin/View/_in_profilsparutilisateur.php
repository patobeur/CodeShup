          <!-- Begin Page Content -->
          <div class="container-fluid">

            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">{{test}}</h1>
            <p class="mb-4">liste des profils par utilisateurs<a target="_blank" href="https://datatables.net">official DataTables documentation</a>.</p>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">DataTables [z_profil]</h6>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                    <thead>
                      <tr>
                        <th>#</th>
                        <th>prenom</th>
                        <th>nom</th>
                        <th>email</th>
                        <th>mobil</th>
                        <th>naissance</th>
                        <!-- <th>section_id</th>
                        <th>promo_id</th>
                        <th>last_update</th>
                        <th>created</th>
                        <th>activated</th> -->
                        <th>box</th>
                      </tr>
                    </thead>

                    <tfoot>
                      <tr>
                        <th>#</th>
                        <th>prenom</th>
                        <th>nom</th>
                        <th>email</th>
                        <th>mobil</th>
                        <th>naissance</th>
                        <!-- <th>section_id</th>
                        <th>promo_id</th>
                        <th>last_update</th>
                        <th>created</th>
                        <th>activated</th> -->
                        <th>box</th>
                      </tr>
                    </tfoot>

                    <tbody>
                        {{TABLE}}
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

          </div>
          <!-- /.container-fluid -->