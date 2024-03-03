<div class="updated" style="border: 1px solid #c3c4c7; padding: 15px">
	<h1 style="padding: 0;">We hope you love <?php echo esc_attr($args['title']); ?>.</h1>

	<p style="margin-bottom: 10px;">
		<?php echo esc_attr($args['description']); ?>
	</p>

	<div style="margin-bottom: 15px;">
		<a href="<?php echo esc_attr($args['termsUrl']); ?>" style="text-decoration: none;">Terms of service</a> &nbsp; &nbsp;
		<a href="<?php echo esc_attr($args['policyUrl']); ?>" style="text-decoration: none;">Privacy Policy</a>
	</div>

	<div>
		<a href="<?php echo esc_attr(esc_url($args['optInUrl'])); ?>" class="button-primary button-large">Allow & Continue</a> &nbsp;
		<a href="<?php echo esc_attr(esc_url($args['optOutUrl'])); ?>" class="button-secondary button-large" style="border-color: transparent">Skip</a>
	</div>
</div>