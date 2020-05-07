									<!-- article -->
									<div class="col-xl-3 col-lg-4 col-md-4 mb-8">
										<div class="mobilbar_zone">
											<div class="card-header">
												{{articleCategorie}}
											</div>

											<div class="mobilbar_img set-bg card-img-top" src="{{articleImage}}" alt="{{altImage}}">
												<ul class="mobilbar">
													{{addPanier}}
													{{supPanier}}
													{{urlPanier}}
												</ul>
												<div class="card-body">
													<h4 class="card-title"><a href="#">nom : {{articleName}}</a></h4>
													<!-- <p class="card-text">{{articleContent}}</p> -->
												</div>
											</div>
											<div class="card-footer text-center">
												<span>{{articlePrix}} €</span>
												<span>reste {{articleStock}} articles</span>
											</div>
											<div class="card-footer text-center">
												<smallsmall class="text-muted">★ ★ ★ ★ ☆{{articleEvaluation}}</smallsmall>
												<button>Vendu par: {{nameVendor}}</button>
											</div>
										</div>
									</div>
									<!-- /article -->