          <!-- Begin Page Content -->
          <div class="container-fluid">

            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">{{test}}</h1>
            <p class="mb-4">liste des utilisateurs <a target="_blank" href="https://datatables.net">official DataTables documentation</a>.</p>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">DataTables [z_user]</h6>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Email</th>
                        <th>Rule_id</th>
                        <th>created</th>
                        <th>updated</th>
                        <th>last_connect</th>
                        <th>box</th>
                      </tr>
                    </thead>

                    <tfoot>
                      <tr>
                        <th>#</th>
                        <th>Email</th>
                        <th>Rule_id</th>
                        <th>created</th>
                        <th>updated</th>
                        <th>last_connect</th>
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