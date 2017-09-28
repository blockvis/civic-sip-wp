(function( $ ) {
	'use strict';

    var civicSip = new civic.sip({ appId: civic_app.id });
    // Start scope request.
    $('button.js-civic-signup').click(function(event) {
        civicSip.signup({ style: 'popup', scopeRequest: civicSip.ScopeRequests.BASIC_SIGNUP });
    });

    // Listen for data
    civicSip.on('auth-code-received', function (event) {
        // Send JWT token to app server
        sendAuthCode(event.response);
    });

    civicSip.on('user-cancelled', function (event) {
        // Handle request cancellation if necessary.
    });

    // Error events.
    civicSip.on('civic-sip-error', function (error) {
        // Handle error display if necessary.
    });

    function sendAuthCode(jwtToken) {
        console.log('Sending Authorization Code: ', jwtToken);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: civic_ajax.url,
            data: {
                'action': civic_ajax.action,
                'nonce': civic_ajax.nonce,
                'token': jwtToken
            },
            success: function (response) {
                console.log("Auth Response", response);
                if (response.data.logged_in == true) {
                    document.location.href = civic_ajax.redirect_url;
                } else {
                    $('body').addClass('civic-hit-the-lights civic-no-scroll');
                    var modal = $('div.civic-modal.civic-qrcode').addClass('civic-show');
                    modal.find('iframe').remove();
                    modal.find('.civic-content').append(
                        // TODO: move rendering to php
                        $('<div class="civic-onboarding">' +
                            '<div class="civic-informant civic-form-modal">' +
                            '  <div id="civic-retry-box" class="civic-top civic-switch">' +
                            '      <h3>It looks like you are new to this site. <br> Please register using ' + response.data.email +
                            '      </h3>' +
                            '      <div class="civic-button-container two-up">' +
                            '          <button type="button" id="civic-register" class="medium outline">Register</button>' +
                            '          <div class="civic-help-links">' +
                            '              <a id="civic-cancel" href="">Cancel</a>' +
                            '          </div>' +
                            '      </div>' +
                            '  </div>' +
                            '</div>' +
                          '</div>')
                    ).attr('style', ''); // Removes style height.

                    $('#civic-register').on('click', function() {
                        $.ajax({
                            type: 'POST',
                            url: civic_ajax.url, // TODO: chacnge to the new endpoint
                            success: function () {
                                document.location.href = civic_ajax.redirect_url;
                            }
                        });
                    });

                    $('#civic-cancel').on('click', function() {
                        modal.removeClass('civic-show');
                        $('body').removeClass('civic-hit-the-lights civic-no-scroll');
                    });
                }
            }
        });
    }

})( jQuery );
