jQuery(function ($) {
    'use strict';
    var fs = ( $('#blueimp-gallery').attr('data-fullscreen') == '1' );
    var borderless = ( $('#blueimp-gallery').attr('data-useBootstrapModal') == '1' );
    $('#blueimp-gallery').data( 'fullScreen', fs );
    $('#blueimp-gallery').data( 'useBootstrapModal', !borderless );
    $('#blueimp-gallery').toggleClass('blueimp-gallery-controls', borderless);
});
