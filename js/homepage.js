var model = (function(){

	var ImageModel = Backbone.Model.extend({
		defaults: {
				is_featured: 0,
				is_active: 0,
				expires: new Date(),
				image_source: undefined,
				title: "N/A",
				record_id: -1
			},
		
		initialize: function(){ //just make sure to init everything
			this.is_featured = this.is_featured || this.defaults.is_featured;
			this.is_active = this.is_active || this.defaults.is_active;
			this.expires = Date.parse(this.expires) || this.defaults.expires;
			this.image_source = this.image_source || this.defaults.image_source;
			this.title = this.title || this.defaults.title;
			this.record_id = this.record_id || this.defaults.record_id;
		}
	});

	var ImageCollection = Backbone.QueryCollection.extend({
		model: ImageModel	
	});

	var allImages = new ImageCollection();
	var activeImages = new ImageCollection();
	
	var ImageView = Backbone.View.extend({

		tagName: 'li',
		className: 'span4',
	
		template: _.template($('#image-template').html()),

		render: function(){
			var html = this.template(this.model.toJSON());
			this.$el.html(html);
			return this;
		}
	});

	var ImageListView = Backbone.View.extend({
		
		tagname: 'ul',

		initialize: function(){ //bind a couple of selectors
			activeImages.bind('add', this.addOne, this);
			activeImages.bind('reset', this.clearRender, this); 
		},

		addOne: function(image){
			var view = new ImageView({model: image});
			this.$el.append(view.render().el); 	
		},

		clearRender: function(){
			this.$el.empty();
		}
	});

	var listView = new ImageListView({el: $("#images")});

	var App = Backbone.View.extend({

		searchTerms: [],
		

		initialize: function(){
			allImages.bind('add', this.addImage, this);			
		},

		render: function(){
			
		},

		events: {
			'hover .search-category': 'mouseOverCategory',
			'click #search-all': 'searchAll',
			'click #search-featured': 'searchFeatured',
			'click #search-active': 'searchActive',
			'keypress #title-search': 'searchByTitle' 	
		},


	/* ----------- Events bound to the collection -------------*/
		addImage: function(image){
			var isActive = true;

			for(var i = 0; i < this.searchTerms.length; i++){ //loop over everything we are searching for
				if(searchTerms[i] === "expires"){ //if we are searching by expiration, check that this expires after the expiration date
					var expirationDate = searchTerms["expires"]["value"];
					if(!(image["expires"] > expirationDate)){
						isActive = false;
						break;
					}
				}
				else{
					if(image[searchTerms[i]] != searchTerms[i]["value"]){ //otherwise check that the image matches a search value
						isActive = false;
						break;
					}
				}
			}

			if(isActive){
				activeImages.add(image);
			}
		},
	/* ----------- Events bound to DOM triggers ---------------*/
		mouseOverCategory: function(event){
			$(event.currentTarget).toggleClass('gray-background');
		},

		searchAll: function(){ //just gets all values
			this.searchTerms = [];
			var all = allImages.toArray();
			this.filterSelected(all);
		},

		searchFeatured: function(){ //search for featured images
			this.searchTerms = [];
			var featured = allImages.where({'is_featured': '1'});
			this.filterSelected(featured);
		},

		searchActive: function(){ //searches for all active images
			this.searchTerms = [];
			var active = allImages.where({'is_active': '1'});
			this.filterSelected(active);
		},

			
		searchByTitle: function(event){
			if(event.originalEvent.keyCode == 13){ //if its an enter press
				var title = $(event.currentTarget).val();
				var matched = allImages.query({'title': {$like: title}});
				this.filterSelected(matched);		
			}
			
		},

		filterSelected: function(imagesToAdd){
			activeImages.reset();
			activeImages.add(imagesToAdd);
		}

	});

	var application = new App({el: $("#image-list")});

	return {
		load: function(jsonModel){
			var imagesToAdd = JSON.parse(jsonModel);
			
			for(var i = 0; i < imagesToAdd.length; i++){
				allImages.add(imagesToAdd[i]);
			}
		}
	}
})();
