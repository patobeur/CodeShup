            <!-- LOGIN -->
            <!-- <section class="classeur"> -->

                <div class="row justify-content-center connect">

                    <div class="col-xl-10 col-lg-12 col-md-9">

                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image">
                            <img src="theme/img/login.svg" alt="connection"></div>
                            <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Bonjour à vous !</h1>
                                </div>




                                <form class="user" action="#" method="post">

                                <div class="form-group">
                                    <input
                                        id="login"
                                        name="login"
                                        type="login"
                                        value="{{email}}"
                                        class="form-control form-control-user"
                                        aria-describedby="emailHelp"
                                        placeholder="Entrez votre email..."
                                        onfocus="this.placeholder = ''"
                                        onblur="this.placeholder = 'Entrez votre email...'">
                                        {{login}}
                                </div>

                                <div class="form-group">
                                    <input
                                        id="password"
                                        name="password"
                                        type="password"
                                        class="form-control form-control-user"
                                        placeholder="mot de passe"
                                        onfocus="this.placeholder = ''"
                                        onblur="this.placeholder = 'mot de passe'">
                                        {{password}}
                                </div>


                                <div class="form-group">
                                    <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" class="custom-control-input" id="souvenir">
                                    <label class="custom-control-label" for="souvenir">Se souvenir de moi !</label>
                                    </div>
                                </div>
                                <input id="logger" name="logger" type="submit" value="login" class="btn btn-primary btn-dark btn-block">
                                </form>




                                <hr>
                                <!-- <div class="text-center">
                                <a class="small" href="forgot-password.html">Forgot Password?</a>
                                </div>
                                <div class="text-center">
                                <a class="small" href="register.html">Create an Account!</a>
                                </div> -->
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    </div>

                </div>
            <!-- </section> -->
            <!-- END's LOGIN -->