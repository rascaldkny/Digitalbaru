<div class="container produk-description mb-5 p-5">
	<!-- Alert -->
	<?php $this->load->view('layouts/_alert') ?>
	<!-- End of alert -->
	<!-- Hero -->
	<div class="produk mt-5">
		<div class="image-produk ml-4">
			<div class="row">		
				<div class="col">
					<img src="<?= base_url() ?>/images/game/<?= $game['image'] ?>" class="card-img-top image-detail-product" alt="<?= $game['name'] ?>" style="width:330px;">
				</div>	
			</div>
		</div>
		<div class="detail-produk">
				<h2 class="font-weight-bold"><?= $game['name'] ?></h2>
				<h4 class="font-weight-normal"><?=  ucfirst($game['edition']) ?> Edition</h4>
				<p class="font-weight-bold">EDITION</p>
				<h5><span class="badge badge-info badge-pill p-2"><?=  ucfirst($game['edition']) ?></span></h5>	
				<div class="">
					<div class="text-info font-weight-bold">
						<h6 class="badge badge-info badge-pill p-2 mt-3">Rp.<?= number_format($game['price']); ?></h2>
						<form action="<?= base_url('cart/add') ?>" method="POST">
							<input type="hidden" name="product_id" value="<?= $game['id'] ?>">
							<button type="submit" class="btn btn-large btn-success btn-block badge badge-info badge-pill p-2 mt-5">ADD</button>
						</form>
					</div>
				</div>
		</div>
	</div>	
	
	<!-- End of hero -->

	<!-- description -->
	<div class="description-produk">
		<div class="row mt-5 mb-2">
			<div class="col">
			<h3 id="description">Description</h3>
		</div>
	</div>
	<div class="row">
		<div class="col bg-light p-5">
			<?= $game['description'] ?>
		</div>
	</div>
	</div>
	<!-- End of description -->
	<!-- System requirements -->
	<div class="requirements-produk">

	
	<div class="row mt-5 mb-2">
		<div class="col">
			<h3>System Requirements</h3>
		</div>
	</div>
	
	<div class="row mb-5">
		<div class="col bg-light p-5">
			<?= $game['requirements'] ?>
		</div>
	</div>
	</div>
	<!-- End of System requirements -->
</div>

