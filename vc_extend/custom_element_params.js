(function($) {
	window.VcCustomElementView = vc.shortcode_view.extend( {
		elementTemplate: false,
		$wrapper: false,
		changeShortcodeParams: function ( model ) {
			var params;

			window.VcCustomElementView.__super__.changeShortcodeParams.call( this, model );
			params = _.extend( {}, model.get( 'params' ) );
			if ( ! this.elementTemplate ) {
				this.elementTemplate = this.$el.find( '.vc_custom-element-container' ).html();
			}
			if ( ! this.$wrapper ) {
				this.$wrapper = this.$el.find( '.wpb_element_wrapper' );
			}
			if ( _.isObject( params ) ) {
				var template = vc.template( this.elementTemplate, vc.templateOptions.custom );
				this.$wrapper.find( '.vc_custom-element-container' ).html( template( { params: params } ) );
			}
		}
	} );
})(window.jQuery)