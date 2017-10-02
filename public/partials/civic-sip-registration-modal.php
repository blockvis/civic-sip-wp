<div class="civic-onboarding">
    <div class="civic-informant civic-form-modal">
        <div class="civic-top civic-switch">
            <h3><?php printf(__('It looks like you are new to this site. <br>
                Do you want to register as %s?', 'civic-sip'), $email) ?></h3>
            <div class="civic-button-container two-up">
                <button type="button" id="civic-register" class="medium outline">
                    <?php _e('Register', 'civic-sip') ?>
                </button>
                <div class="civic-help-links">
                    <a id="civic-cancel" href=""><?php _e('Cancel', 'civic-sip') ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
