<div class="container">
	<div class="row mt-4">
		<div class="col">

			<?php $this->load->view('layouts/_alert') ?>

			<div class="card">
				<h5 class="card-header text-center"><strong>My Orders</strong></h5>
				<div class="card-body">
					<table class="table table-bordered text-center">
						<thead>
							<tr>
								<th>Invoice</th>
								<th>Date</th>
								<th>Total</th>
								<th>Status</th>
								<th>Expired Time</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($orders as $o) : ?>
								<tr>
									<td><strong><a href="<?= base_url('myorder/detail/' . $o['invoice']) ?>"><?= $o['invoice'] ?></a></strong></td>
									<td><?= $o['date'] ?></td>
									<td>Rp. <?= number_format($o['total'], 2, ',', '.') ?></td>
									
									<td><?php if($o['status'] == 'REQUEST') : ?>
										<span class="badge badge-primary">Menunggu Pembayaran</span>
									<?php elseif($o['status'] == 'SUCCESS') : ?>
										<span class="badge badge-success text-light">Sudah Dibayar</span>
									<?php elseif($o['status'] == 'CANCEL') : ?>
										<span class="badge badge-warning">Dibatalkan</span>
									<?php elseif($o['status'] == 'FAILED') : ?>
										<span class="badge badge-danger">Gagal</span>
									<?php elseif($o['status'] == 'EXPIRED') : ?>
										<span class="badge badge-danger">Expired VA</span>
									<?php endif; ?></td>
									<td>
										<?= date('F j Y H:i:s', strtotime($o['expired_time']));?>
									</td>
									<td><a href="<?= base_url('myorder/cancel/' . $o['id']) ?>" class="badge badge-danger" onclick="return confirm('Apakah yakin transaksi akan di batalkan ?')">Batalkan</a></td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
