<div class="container">
	<div class="row justify-content-center mt-4">
		<div class="col-12">
			<div class="card">
				<h5 class="card-header text-center"><strong>Order Detail #<?= $order['invoice'] ?></strong></h5>
				<div class="card-body">
					<ul>
						<li>Date : <?= $order['date'] ?></li>
						<li>Name    : <?= $order['name'] ?></li>
						<li>Phone : <?= $order['phone'] ?></li>
						<li>Address  : <?= $order['address'] ?></li>
						<li>Status  : 
							<?php if( $detailTransaksi['status'] == 'REQUEST') : ?>
								<span class="badge badge-primary">Menunggu Pembayaran</span>
							<?php elseif($detailTransaksi['status'] == 'SUCCESS') : ?>
								<span class="badge badge-success text-light">Sudah Dibayar</span>
							<?php elseif($detailTransaksi['status'] == 'CANCEL') : ?>
								<span class="badge badge-warning">Dibatalkan</span>
							<?php elseif($detailTransaksi['status'] == 'FAILED') : ?>
								<span class="badge badge-danger">Gagal</span>
							<?php elseif($detailTransaksi['status'] == 'EXPIRED') : ?>
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
								<!-- <th>Action</th> -->
							</tr>
						</thead>
						<tbody>
							<?php foreach($order_detail as $od) : ?>
								<?php
								$get_detail_status_uniplay = $this->order->getOrderDetailUniplay($od['id']);
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
													if($detailTransaksi['status'] == 'REQUEST') {
														echo "Transaction hold";
													} else {
														echo "Transaction failed";
													}
												}
											}
										} else {
											echo $detailTransaksi['status'];
										}
										?>
									</td>
									<td><h5>Rp. <?= number_format($od['subtotal'], 2, ',', '.') ?></h5></td>
									<!-- <td> -->
										<?php
										// if($get_detail_status_uniplay['inquiry_id'] != NULL && $get_detail_status_uniplay['order_id'] == NULL) {
										// 	echo "process manual";
										// } else {
										// 	echo "-";
										// }
										?>
									<!-- </td> -->
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
								<!-- <td></td> -->
							</tr>
						</tfoot>
					</table>
				</div>
				
			</div>
		</div>
	</div>
	<div class="row mt-3 mb-5">
		<div class="col-8">
			<div class="card">
				<h5 class="card-header">Payments Confirmation</h5>
				<div class="card-body">
					<p>Virtual Account Number: <strong class="text-info"><?= $detailTransaksi['payment_code']?></strong></p>
					<?php
					$bank = explode("_",$detailTransaksi['payment_channel']);
					?>
					<p>Bank Name: <strong class="text-info"><?= $bank[1]?></strong></p>
					<p>Nominal: <strong class="text-info">Rp.<?=number_format($detailTransaksi['amount'])?> </strong></p>
				</div>
			</div>
		</div>
	</div>
</div>
