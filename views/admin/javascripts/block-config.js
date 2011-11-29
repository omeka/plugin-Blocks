
var Blocks = {
		
	actionsForPlugin: function(module) {
		actions = [];
		for (var route in Blocks.routes) {
			if(Blocks.routes[route].module == module) {
				if(Blocks.routes[route].action) {
					if(actions.indexOf(Blocks.routes[route].action) == -1) {
						actions[actions.length] = Blocks.routes[route].action;
					}
					
				}
			}
		}
		return actions;
	},

	controllersForPlugin: function(module) {
		controllers = [];
		for (var route in Blocks.routes) {
			if(Blocks.routes[route].module == module) {
				if(Blocks.routes[route].controller) {
					if(controllers.indexOf(Blocks.routes[route].controller) == -1) {
						controllers[controllers.length] = Blocks.routes[route].controller;
					}
					
				}
			}
		}
		return controllers;
	},

	actionsForController: function(controller) {
		actions = [];
		for (var route in Blocks.routes) {
			if(Blocks.routes[route].controller == controller) {
				if(Blocks.routes[route].action) {
					if(actions.indexOf(Blocks.routes[route].action) == -1) {
						actions[actions.length] = Blocks.routes[route].action;
					}
					
				}
			}
		}
		return actions;
	},

	applyFiltersByPlugin: function() {
		module = jQuery('#omeka_module').val();
		if(module == 'any') {
			jQuery('#controller option').each(function(index) {
					jQuery(this).show();
			});
			return;
		}
		actions = Blocks.actionsForPlugin(module);
		controllers = Blocks.controllersForPlugin(module);
		
		jQuery('#controller option').each(function(index) {
			if(this.value != 'any' && controllers.indexOf(this.value) == -1) {
				jQuery(this).hide();
			}

		});

		jQuery('#action option').each(function(index) {
			if(this.value != 'any' && actions.indexOf(this.value) == -1) {
				jQuery(this).hide();
			}

		});
		
	},

	applyFiltersByController: function() {
		controller = jQuery('#controller').val();
		if(controller == 'any') {
			jQuery('#action option').each(function(index) {
					jQuery(this).show();
			});
			return;
		}

		actions = Blocks.actionsForController(controller);
		
		jQuery('#action option').each(function(index) {
			if(this.value != 'any' && actions.indexOf(this.value) == -1) {
				jQuery(this).hide();
			}

		});
	},
	
	attachFilters: function() {
		jQuery('#omeka_module').each(function(i) {
			this.onchange = Blocks.applyFiltersByPlugin;
		});
		jQuery('#controller').each(function(i) {
			this.onchange = Blocks.applyFiltersByController;
			
		} );
	}

	
		
};

jQuery(document).ready(Blocks.attachFilters);