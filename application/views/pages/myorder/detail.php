<div class="container">
	<div class="row justify-content-center mt-4">
		<div class="col-12">
			<div class="card">
				<h5 class="card-header text-center"><strong>Orders Detail #<?= $order['invoice'] ?></strong></h5>
				<div class="card-body">
					<ul>
						<li>Date : <?= $order['date'] ?></li>
						<li>Name    : <?= $order['name'] ?></li>
						<li>Phone : <?= $order['phone'] ?></li>
						<li>Address  : <?= $order['address'] ?></li>
						<li>Total  : Rp.  <?= number_format($detail['amount']) ?></li>
						<li>Deskripsi  : <?= $detail['payment_info'] ?></li>
						<li>Kode Pembayaran  : <strong><?= $detail['payment_code'] ?> </strong></li>
						<li>Jenis Pembayaran  : Virtual Account <?= $bank['nama_bank'] ?></li>
						<li>Status  : 
							<?php if($detail['status'] == 'REQUEST') : ?>
								<span class="badge badge-primary">Menunggu Pembayaran</span>
							<?php elseif($detail['status'] == 'SUCCESS') : ?>
								<span class="badge badge-success text-light">Sudah Dibayar</span>
							<?php elseif($detail['status'] == 'CANCEL') : ?>
								<span class="badge badge-warning">Dibatalkan</span>
							<?php elseif($detail['status'] == 'FAILED') : ?>
								<span class="badge badge-danger">Gagal</span>
							<?php elseif($detail['status'] == 'EXPIRED') : ?>
								<span class="badge badge-danger">Expired VA</span>
							<?php endif; ?>
						</li>
					</ul>

					<table class="table table-bordered text-center">
						<thead class="thead-dark">
							<tr>
								<th>Game</th>
								<th>Kategori</th>
								<th>No Voucher</th>
								<th>Status</th>
								<th>Price</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($order_detail as $od) : ?>
								<?php
								$get_detail_status_uniplay = $this->myorder->getOrderDetailUniplay($od['id']);
								?>
								<tr>
									<td>
										<img src="<?= base_url('images/game/' . $od['image']) ?>" style="width:200px">
									</td>
									<td><?=$od['kategori_name']?></td>
									<td>
										<?=$get_detail_status_uniplay['code_voucher'] != "" ? $get_detail_status_uniplay['code_voucher'] : "---" ?>
									</td>
									<td>
										<?php
										if($od['kategori'] != 1) {
											if($get_detail_status_uniplay['inquiry_id'] != NULL && $get_detail_status_uniplay['order_id'] == NULL) {
												echo "Transaction is being processed";
											} else {
												if($get_detail_status_uniplay['status_uniplay'] == "done" || $get_detail_status_uniplay['status_uniplay'] == "payment_received") {
													echo "Transaction successful";
												} else {
													if($detail['status'] == 'SUCCESS') {
														echo "Transaction is being processed";
													} else {
														echo "Transaction failed";
													}
												}
											}
										} else {
											echo $detail['status'];
										}
										?>
									</td>
									<td><h5>Rp. <?= number_format($od['subtotal'], 2, ',', '.') ?></h5></td>
								</tr>
							<?php endforeach ?>
						</tbody>
						<tfoot class="bg-success text-light">
							<tr>
								<td><strong>Total</strong></td>
								<td></td>
								<td></td>
								<td></td>
								<td><h5><strong>Rp. <?= number_format(array_sum(array_column($order_detail, 'subtotal')), 2, ',', '.') ?></strong></h5></td>
							</tr>
						</tfoot>
					</table>

					<hr>
					<div class="text-center text-info">
						<small class="text-dark">If you need help or information, you can contact this.</small>
						<br>
						<small>digitalbaruberjaya@gmail.com | 0813 8547 12920</small>
					</div>

					<?php if($order['status'] == 'waiting') : ?>
						<form action="<?= base_url('myorder/confirm/' .  $order['invoice']) ?>" method="POST">
							<button type="submit" class="btn btn-info btn-sm float-right">Payment Confirm</button>
						</form>
					<?php endif ?>

				</div>
			</div>
		</div>
	</div>
</div>
