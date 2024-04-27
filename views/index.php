<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<?php if (staff_can('create', 'chat') || staff_can('view', 'chat')): ?>
				<div class="_buttons tw-mb-2 sm:tw-mb-4">
					<?php if (staff_can('create', 'chat')): ?>
					<a href="<?php echo admin_url('chat/send'); ?>" class="btn btn-primary pull-left display-block">
						<i class="fa-regular fa-plus tw-mr-1"></i>
						Send New Message
					</a>
					<?php endif; ?>
					<div class="clearfix"></div>
				</div>
				<?php endif; ?>
				<div class="panel_s">
					<div class="panel-body panel-table-full">
						<h1>Chat Module</h1>
						<?php if ($this->session->flashdata('message')): ?>
						<p><?= $this->session->flashdata('message'); ?></p>
						<?php endif; ?>
						<h2>Send Message</h2>
						<form method="post" action="<?= site_url('admin/chat/send'); ?>">
							<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
							<input type="text" name="phone_number" required placeholder="Phone Number">
							<textarea name="message" required placeholder="Your message"></textarea>
							<button type="submit" class="btn btn-info">Send Message</button>
						</form>
						<h2>Messages</h2>
						<table class="table dt-table">
							<thead>
								<tr>
									<th>Phone Number</th>
									<th>Message</th>
									<th>Direction</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($messages as $msg): ?>
								<tr>
									<td><?= $msg->phone_number; ?></td>
									<td><?= $msg->message; ?></td>
									<td><?= $msg->direction; ?></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php init_tail(); ?>
<script>
	$(function() {
		initDataTable('.dt-table', window.location.href, [0, 1, 2], [0, 1, 2]);
	});
</script>
