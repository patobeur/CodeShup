          <!-- Begin Page Content -->
          <div class="container-fluid">

            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">{{TITRE}}</h1>
            <p class="mb-4">Mes informations !</p>
            <div class="row">
<!-- 
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                  <span class="text-muted">Your cart</span>
                  <span class="badge badge-secondary badge-pill">3</span>
                </h4>
                <ul class="list-group mb-3">
                  <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                      <h6 class="my-0">Product name</h6>
                      <small class="text-muted">Brief description</small>
                    </div>
                    <span class="text-muted">$12</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                      <h6 class="my-0">Second product</h6>
                      <small class="text-muted">Brief description</small>
                    </div>
                    <span class="text-muted">$8</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                      <h6 class="my-0">Third item</h6>
                      <small class="text-muted">Brief description</small>
                    </div>
                    <span class="text-muted">$5</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between bg-light">
                    <div class="text-success">
                      <h6 class="my-0">Promo code</h6>
                      <small>EXAMPLECODE</small>
                    </div>
                    <span class="text-success">-$5</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between">
                    <span>Total (USD)</span>
                    <strong>$20</strong>
                  </li>
                </ul> 
                <form class="card p-2">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Promo code">
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-secondary">Redeem</button>
                    </div>
                  </div>
                </form>

-->
              <div class="col-md-12 order-md-2 mb-4">
                <h4 class="mb-3">Un profil de : {{USERNAME}}</h4>



                <form class="needs-validation" {{postattributs}} novalidate >
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="firstName">Prénom</label>
                      <input type="text" class="form-control" id="firstName" placeholder="" value="{{username}}" required>
                      <div class="invalid-feedback">
                        Votre prénonm
                      </div>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="lastName">Nom</label>
                      <input type="text" class="form-control" id="lastName" placeholder="" value="{{firstname}}" required>
                      <div class="invalid-feedback">
                        Votre nom
                      </div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label for="pseudo">Pseudo</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">@</span>
                      </div>
                      <input type="text" class="form-control" id="pseudo" placeholder="Pseudo" value="{{pseudo}}" required>
                      <div class="invalid-feedback" style="width: 100%;">
                        Trouvez un pseudo unique !
                      </div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label for="phone">N° Téléphone</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">@</span>
                      </div>
                      <input type="text" class="form-control" id="phone" placeholder="N° Téléphone" value="{{phone}}" required>
                      <div class="invalid-feedback" style="width: 100%;">
                          un Numéro de Téléphone utile au livreur !
                      </div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label for="email">Email <span class="text-muted">(Optional)</span></label>
                    <input type="email" class="form-control" id="email" value="{{email}}" placeholder="prenom.nom@example.com">
                    <div class="invalid-feedback">
                      Renseignez un mail valide.
                    </div>
                  </div>

                  <div class="mb-3">
                    <label for="address">Adresse</label>
                    <input type="text" class="form-control" id="address" value="{{adresse}}" placeholder="1 rue du point virgule" required>
                    <div class="invalid-feedback">
                      Renseignez votre adresse de facturation
                    </div>
                  </div>

                  <div class="mb-3">
                    <label for="address2">Précision Adresse</label>
                    <label for="address2">Adresse 2<span class="text-muted">(Optional)</span></label>
                    <input type="text" class="form-control" id="address2" value="{{adresse2}}" placeholder="Appartement ou suite">
                  </div>

                  <div class="row">
                    <div class="col-md-5 mb-3">
                      <label for="pays">Pays</label>
                      <select class="custom-select d-block w-100" id="pays" required>
                        <option value="">Choose...</option>
                        <optgroup label="Afrique">
<option value="afriqueDuSud">Afrique Du Sud</option>
<option value="algerie">Algérie</option>
<option value="angola">Angola</option>
<option value="benin">Bénin</option>
<option value="botswana">Botswana</option>
<option value="burkina">Burkina</option>
<option value="burundi">Burundi</option>
<option value="cameroun">Cameroun</option>
<option value="capVert">Cap-Vert</option>
<option value="republiqueCentre-Africaine">République Centre-Africaine</option>
<option value="comores">Comores</option>
<option value="republiqueDemocratiqueDuCongo">République Démocratique Du Congo</option>
<option value="congo">Congo</option>
<option value="coteIvoire">Côte d'Ivoire</option>
<option value="djibouti">Djibouti</option>
<option value="egypte">Égypte</option>
<option value="ethiopie">Éthiopie</option>
<option value="erythrée">Érythrée</option>
<option value="gabon">Gabon</option>
<option value="gambie">Gambie</option>
<option value="ghana">Ghana</option>
<option value="guinee">Guinée</option>
<option value="guinee-Bisseau">Guinée-Bisseau</option>
<option value="guineeEquatoriale">Guinée Équatoriale</option>
<option value="kenya">Kenya</option>
<option value="lesotho">Lesotho</option>
<option value="liberia">Liberia</option>
<option value="libye">Libye</option>
<option value="madagascar">Madagascar</option>
<option value="malawi">Malawi</option>
<option value="mali">Mali</option>
<option value="maroc">Maroc</option>
<option value="maurice">Maurice</option>
<option value="mauritanie">Mauritanie</option>
<option value="mozambique">Mozambique</option>
<option value="namibie">Namibie</option>
<option value="niger">Niger</option>
<option value="nigeria">Nigeria</option>
<option value="ouganda">Ouganda</option>
<option value="rwanda">Rwanda</option>
<option value="saoTomeEtPrincipe">Sao Tomé-et-Principe</option>
<option value="senegal">Séngal</option>
<option value="seychelles">Seychelles</option>
<option value="sierra">Sierra</option>
<option value="somalie">Somalie</option>
<option value="soudan">Soudan</option>
<option value="swaziland">Swaziland</option>
<option value="tanzanie">Tanzanie</option>
<option value="tchad">Tchad</option>
<option value="togo">Togo</option>
<option value="tunisie">Tunisie</option>
<option value="zambie">Zambie</option>
<option value="zimbabwe">Zimbabwe</option>
</optgroup>
<optgroup label="Amérique">
<option value="antiguaEtBarbuda">Antigua-et-Barbuda</option>
<option value="argentine">Argentine</option>
<option value="bahamas">Bahamas</option>
<option value="barbade">Barbade</option>
<option value="belize">Belize</option>
<option value="bolivie">Bolivie</option>
<option value="bresil">Brésil</option>
<option value="canada">Canada</option>
<option value="chili">Chili</option>
<option value="colombie">Colombie</option>
<option value="costaRica">Costa Rica</option>
<option value="cuba">Cuba</option>
<option value="republiqueDominicaine">République Dominicaine</option>
<option value="dominique">Dominique</option>
<option value="equateur">Équateur</option>
<option value="etatsUnis">États Unis</option>
<option value="grenade">Grenade</option>
<option value="guatemala">Guatemala</option>
<option value="guyana">Guyana</option>
<option value="haiti">Haïti</option>
<option value="honduras">Honduras</option>
<option value="jamaique">Jamaïque</option>
<option value="mexique">Mexique</option>
<option value="nicaragua">Nicaragua</option>
<option value="panama">Panama</option>
<option value="paraguay">Paraguay</option>
<option value="perou">Pérou</option>
<option value="saintCristopheEtNieves">Saint-Cristophe-et-Niévès</option>
<option value="sainteLucie">Sainte-Lucie</option>
<option value="saintVincentEtLesGrenadines">Saint-Vincent-et-les-Grenadines</option>
<option value="salvador">Salvador</option>
<option value="suriname">Suriname</option>
<option value="triniteEtTobago">Trinité-et-Tobago</option>
<option value="uruguay">Uruguay</option>
<option value="venezuela">Venezuela</option>
</optgroup>
<optgroup label="Asie">
<option value="afghanistan">Afghanistan</option>
<option value="arabieSaoudite">Arabie Saoudite</option>
<option value="armenie">Arménie</option>
<option value="azerbaidjan">Azerbaïdjan</option>
<option value="bahrein">Bahreïn</option>
<option value="bangladesh">Bangladesh</option>
<option value="bhoutan">Bhoutan</option>
<option value="birmanie">Birmanie</option>
<option value="brunei">Brunéi</option>
<option value="cambodge">Cambodge</option>
<option value="chine">Chine</option>
<option value="coreeDuSud">Corée Du Sud</option>
<option value="coreeDuNord">Corée Du Nord</option>
<option value="emiratsArabeUnis">Émirats Arabe Unis</option>
<option value="georgie">Géorgie</option>
<option value="inde">Inde</option>
<option value="indonesie">Indonésie</option>
<option value="iraq">Iraq</option>
<option value="iran">Iran</option>
<option value="israel">Israël</option>
<option value="japon">Japon</option>
<option value="jordanie">Jordanie</option>
<option value="kazakhstan">Kazakhstan</option>
<option value="kirghistan">Kirghistan</option>
<option value="koweit">Koweït</option>
<option value="laos">Laos</option>
<option value="liban">Liban</option>
<option value="malaisie">Malaisie</option>
<option value="maldives">Maldives</option>
<option value="mongolie">Mongolie</option>
<option value="nepal">Népal</option>
<option value="oman">Oman</option>
<option value="ouzbekistan">Ouzbékistan</option>
<option value="pakistan">Pakistan</option>
<option value="philippines">Philippines</option>
<option value="qatar">Qatar</option>
<option value="singapour">Singapour</option>
<option value="sriLanka">Sri Lanka</option>
<option value="syrie">Syrie</option>
<option value="tadjikistan">Tadjikistan</option>
<option value="taiwan">Taïwan</option>
<option value="thailande">Thaïlande</option>
<option value="timorOriental">Timor oriental</option>
<option value="turkmenistan">Turkménistan</option>
<option value="turquie">Turquie</option>
<option value="vietNam">Viêt Nam</option>
<option value="yemen">Yemen</option>
</optgroup>
<optgroup label="Europe">
<option value="allemagne">Allemagne</option>
<option value="albanie">Albanie</option>
<option value="andorre">Andorre</option>
<option value="autriche">Autriche</option>
<option value="bielorussie">Biélorussie</option>
<option value="belgique">Belgique</option>
<option value="bosnieHerzegovine">Bosnie-Herzégovine</option>
<option value="bulgarie">Bulgarie</option>
<option value="croatie">Croatie</option>
<option value="danemark">Danemark</option>
<option value="espagne">Espagne</option>
<option value="estonie">Estonie</option>
<option value="finlande">Finlande</option>
<option value="france">France</option>
<option value="grece">Grèce</option>
<option value="hongrie">Hongrie</option>
<option value="irlande">Irlande</option>
<option value="islande">Islande</option>
<option value="italie">Italie</option>
<option value="lettonie">Lettonie</option>
<option value="liechtenstein">Liechtenstein</option>
<option value="lituanie">Lituanie</option>
<option value="luxembourg">Luxembourg</option>
<option value="exRepubliqueYougoslaveDeMacedoine">Ex-République Yougoslave de Macédoine</option>
<option value="malte">Malte</option>
<option value="moldavie">Moldavie</option>
<option value="monaco">Monaco</option>
<option value="norvege">Norvège</option>
<option value="paysBas">Pays-Bas</option>
<option value="pologne">Pologne</option>
<option value="portugal">Portugal</option>
<option value="roumanie">Roumanie</option>
<option value="royaumeUni">Royaume-Uni</option>
<option value="russie">Russie</option>
<option value="saintMarin">Saint-Marin</option>
<option value="serbieEtMontenegro">Serbie-et-Monténégro</option>
<option value="slovaquie">Slovaquie</option>
<option value="slovenie">Slovénie</option>
<option value="suede">Suède</option>
<option value="suisse">Suisse</option>
<option value="republiqueTcheque">République Tchèque</option>
<option value="ukraine">Ukraine</option>
<option value="vatican">Vatican</option>
</optgroup>
<optgroup label="Océanie">
<option value="australie">Australie</option>
<option value="fidji">Fidji</option>
<option value="kiribati">Kiribati</option>
<option value="marshall">Marshall</option>
<option value="micronesie">Micronésie</option>
<option value="nauru">Nauru</option>
<option value="nouvelleZelande">Nouvelle-Zélande</option>
<option value="palaos">Palaos</option>
<option value="papouasieNouvelleGuinee">Papouasie-Nouvelle-Guinée</option>
<option value="salomon">Salomon</option>
<option value="samoa">Samoa</option>
<option value="tonga">Tonga</option>
<option value="tuvalu">Tuvalu</option>
<option value="vanuatu">Vanuatu</option>

</optgroup>

                      </select>
                      <div class="invalid-feedback">
                        Please select a valid country.
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="state">State</label>
                      <select class="custom-select d-block w-100" id="state" required>
                        <option value="">Choose...</option>
                        <option>California</option>
                      </select>
                      <div class="invalid-feedback">
                        Please provide a valid state.
                      </div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <label for="zip">Code Postal</label>
                      <input type="text" class="form-control" id="zip" placeholder="code Postal" value="{{zip}}"required>
                      <div class="invalid-feedback">
                        Votre code Postal
                      </div>
                    </div>
                  </div>
                  <hr class="mb-4">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="same-address">
                    <label class="custom-control-label" for="same-address">Shipping address is the same as my billing address</label>
                  </div>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="save-info">
                    <label class="custom-control-label" for="save-info">Save this information for next time</label>
                  </div>
                  <hr class="mb-4">

                  <h4 class="mb-3">Payment</h4>

                  <div class="d-block my-3">
                    <div class="custom-control custom-radio">
                      <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" checked required>
                      <label class="custom-control-label" for="credit">Credit card</label>
                    </div>
                    <div class="custom-control custom-radio">
                      <input id="debit" name="paymentMethod" type="radio" class="custom-control-input" required>
                      <label class="custom-control-label" for="debit">Debit card</label>
                    </div>
                    <div class="custom-control custom-radio">
                      <input id="paypal" name="paymentMethod" type="radio" class="custom-control-input" required>
                      <label class="custom-control-label" for="paypal">PayPal</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="cc-name">Name on card</label>
                      <input type="text" class="form-control" id="cc-name" placeholder="" required>
                      <small class="text-muted">Full name as displayed on card</small>
                      <div class="invalid-feedback">
                        Name on card is required
                      </div>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="cc-number">Credit card number</label>
                      <input type="text" class="form-control" id="cc-number" placeholder="" required>
                      <div class="invalid-feedback">
                        Credit card number is required
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3 mb-3">
                      <label for="cc-expiration">Expiration</label>
                      <input type="text" class="form-control" id="cc-expiration" placeholder="" required>
                      <div class="invalid-feedback">
                        Expiration date required
                      </div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <label for="cc-cvv">CVV</label>
                      <input type="text" class="form-control" id="cc-cvv" placeholder="" required>
                      <div class="invalid-feedback">
                        Security code required
                      </div>
                    </div>
                  </div>
                  <hr class="mb-4">
                  <button class="btn btn-primary btn-lg btn-block" type="submit">Continue to checkout</button>
                </form>
              </div>
            </div>

          </div>
          <!-- /.container-fluid -->