<div class="civic-onboarding">
	<div class="civic-informant civic-form-modal">
		<div class="civic-top civic-switch">
			<?php if ( ! get_option( 'users_can_register' ) ): ?>
				<h3><?php _e( 'User registration is currently not allowed.' ) ?></h3>
			<?php else: ?>
				<h3><?php printf( __( 'It looks like you are new to this site. <br>
                Do you want to register as %s?', 'civic-sip' ), $email ) ?></h3>
			<?php endif ?>
			<div class="civic-button-container two-up">
				<?php if ( get_option( 'users_can_register' )): ?>
					<button type="button" id="civic-register" class="medium outline">
						<?php _e( 'Register', 'civic-sip' ) ?>
					</button>
				<?php endif ?>
				<div class="civic-help-links">
					<a id="civic-cancel" href=""><?php _e( 'Cancel', 'civic-sip' ) ?></a>
				</div>
			</div>
		</div>
	</div>
</div>
