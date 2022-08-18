<div class="container">
	<div class="row mt-4 mb-3">
		<div class="col-11">
			<h2>List Games Uniplay</h2>
		</div>
	</div>

	<?php $this->load->view('layouts/_alert') ?>

	<div class="row mt-3">
		<div class="col">
			<?php if($status !== "200") { ?>
			<?php } else { ?>
				<table class="table table-light text-center">
					<thead class="thead-dark">
						<tr>
							<th>#</th>
							<th style="text-align: left;" width="20%">Name</th>
							<th style="text-align: left;" width="20%">Publisher</th>
							<th style="text-align: left;" width="20%">Category</th>
							<th style="text-align: center;"width="20%">Status</th>
							<th width="20%">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $no = 1; foreach($product as $p) : ?>
							<tr>
								<td><?= $no++ ?></td>
								<td align="left"><?= $p['name'] ?></td>
								<td align="left"><?= substr_replace(ucfirst($p['publisher']), " ...", 20) ?></td>
								<td align="left"><?= ucfirst($p['kategori']) ?></td>
								<!-- Status Pembaharuan dari Uniplay, data sudah tidak ada di uniplay -->
								<!-- Status Pembaharuan dari Uniplay, array denom ada tambahan data denom baru -->
								<!-- Status Pembaharuan dari Uniplay, array denom harga resellernya update dari uniplay -->
								<!-- <td align="left">Published, The price has been determined, Please specify the price, Game is not active from Uniplay, Reseller price changed from Uniplay</td> -->
								<td align="center">Please specify the price</td>
								<td>
									<!-- Tombol edit tampil jika sudah data sudah masuk ke database produk -->
									<a href="<?= base_url('product/edit/' . $p['id']) ?>" class="btn btn-warning btn-sm">
										<i class="fas fa-edit text-light"></i>
									</a>

									<!-- Pengecekan tombol delete tampil jika sudah masuk ke database produk -->
									<!-- Tampilkan popup konfirmasi data yang hapus adalah didatabase akan unpublish game dari dashboard -->
									<!--a href="<?= base_url('product/delete/' . $p['id']) ?>" class="btn btn-danger btn-sm">
										<i class="fas fa-trash"></i>
									</a-->
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			<?php } ?>
		</div>
	</div>
</div>
