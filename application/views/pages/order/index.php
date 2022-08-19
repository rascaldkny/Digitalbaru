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
						<th>Status Mcpayment</th>
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
						<?php if($o['status'] == 'SUCCESS') { ?>
							<?php if(isset($o['order_detail']) && count($o['order_detail']) > 0) { ?>
								<tr>
									<td colspan="4">
										<table class="table table-bordered text-center" style="border: double;">
											<?php foreach ($o['order_detail'] as $key_detail => $value_detail) { ?>
												<?php if($value_detail['kategori'] != 1) { ?>
													<?php
													$get_detail_status_uniplay = $this->order->getOrderDetailUniplay($value_detail['id']);
													if(count($get_detail_status_uniplay) > 0) {
													?>
														<tr style="border: double;"><th colspan="4">Uniplay Status <?=$value_detail['name'];?></th></tr>
														<tr>
															<th>Transaction Date</th>
															<th>Transaction Number</th>
															<?php if($value_detail['kategori'] == 3) { ?>
																<th>Voucher Number</th>
															<?php } ?>
															<th>Status</th>
															<!-- <th>Action</th> -->
														</tr>
														<tr>
															<td><?=$get_detail_status_uniplay['trx_date'] != "" ? $get_detail_status_uniplay['trx_date'] : "---" ;?></td>
															<td><?=$get_detail_status_uniplay['trx_number'];?></td>
															<?php if($value_detail['kategori'] == 3) { ?>
																<td><?=$get_detail_status_uniplay['code_voucher']?></td>
															<?php } ?>
															<td align="center">
																<?php 
																if($get_detail_status_uniplay['inquiry_id'] != NULL && $get_detail_status_uniplay['order_id'] == NULL) {
																	echo "Transaction is being processed";
																} else {
																	if($get_detail_status_uniplay['status_uniplay'] == "done" || $get_detail_status_uniplay['status_uniplay'] == "payment_received") {
																		echo "Transaction successful";
																	} else {
																		echo "Transaction failed";
																	}
																}
																?>
															</td>
															<!-- <td> -->
																<?php
																// if($get_detail_status_uniplay['inquiry_id'] != NULL && $get_detail_status_uniplay['order_id'] == NULL) {
																// 	echo "process manual";
																// } 
																?>
															<!-- </td> -->
														</tr>
														<!-- <tr><td colspan="4">&nbsp;</td></tr> -->
													<?php } ?>
												<?php } ?>
											<?php } ?>
										</table>
									</td>
								</tr>
							<?php } ?>
						<?php } ?>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
