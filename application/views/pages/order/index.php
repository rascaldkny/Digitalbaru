<div class="container">

	<div class="row mt-4">
		<div class="col-10">
			<h3>List Users Orders</h3>
		</div>
	</div>

	<?php $this->load->view('layouts/_alert') ?>

	<div class="row mt-4">
		<div class="col bg-light p-4" style="text-align:right;">
			<a href="<?=site_url('order')?>" class="text-dark my-auto">
				Payment
			</a>
			|
			<a href="<?=site_url('order/expired')?>" class="text-dark my-auto">
				Payment Failed
			</a>
		</div>
	</div>
	<div class="row mt-4">
		<div class="col bg-light p-4">
			<table class="table table-bordered text-center">
				<thead>
					<tr>
						<th>Invoice</th>
						<th>Date</th>
						<th>Total</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($orders as $o) : ?>
						<tr>
							<td><a href="<?= base_url('order/detail/' . $o['id']) ?>"><strong><?= $o['invoice'] ?></strong></a></td>
							<td><?= $o['date'] ?></td>
							<td align="right">Rp. <?= number_format($o['total'], 2, ',', '.') ?></td>
							<td>
								<?php if($o['status'] == 'REQUEST') : ?>
									<span class="badge badge-primary">Menunggu Pembayaran</span>
								<?php elseif($o['status'] == 'SUCCESS') : ?>
									<span class="badge badge-success text-light">Sudah Dibayar</span>
								<?php elseif($o['status'] == 'CANCEL') : ?>
									<span class="badge badge-warning">Dibatalkan</span>
								<?php elseif($o['status'] == 'FAILED') : ?>
									<span class="badge badge-danger">Gagal</span>
								<?php elseif($o['status'] == 'EXPIRED') : ?>
									<span class="badge badge-danger">Expired VA</span>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>

</div>
