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
                }
            }
        });
    }

})( jQuery );
