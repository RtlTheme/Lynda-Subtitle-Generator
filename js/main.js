(function(){
	window.sprite = $('#zipit').sprite({cellSize: [131,54],cells: [1, 15],initCell: [0,0],interval: 50});
	
	window.App = {
		Models:{},
		Views:{},
		Router:{}	
	}
	
	var vent = _.extend({},Backbone.Events);
	
	App.Models.Sub = Backbone.Model.extend({
		defaults:{
			name:'',
			lyndaUrl:'',
			downloadLink:''	
		},
		validate:function(attr){
			if(!$.trim(attr.lyndaUrl) ){
				return 'please enter download link.'	
			}
		}
	});
	
	App.Views.subs = Backbone.View.extend({
		el:'#downloadLink',
		
		initialize:function(){
			this.model.on('change:lyndaUrl',this.changedurl,this)
		},
		changedurl:function(){
			sprite.go();
			
			$.ajax({ 
			  type: 'get', 
			  url: 'index.php',
			  data:{ url: this.model.get('lyndaUrl'),api:1},
			  success: function(data) {
				  console.log(data);
				  $('#downloadLink').attr('href',data);
				  $('#inputs').addClass('flipped');
				  	
			  },
			  error: function(xhr, ajaxOptions, thrownError) {
			      console.log(thrownError);
			  }
			});
		}
		
	})
	
	App.Views.submitURL = Backbone.View.extend({
		el:'#lyndaURL',
		events:{
			'submit':'submit'	
		},
		submit:function(e){
			e.preventDefault();
			var newURL = $(e.currentTarget).find('input[type=text]').val();
			var pat = /lynda.com/i;
			if(pat.test(newURL)){
				this.model.set('lyndaUrl',newURL);
			}else{
				vent.trigger('showErr','Oops, check your url please!')	
			}
		}
	});
	
	App.Views.err = Backbone.View.extend({
		tagName:'div',
		id:'err',
		initialize:function(){
			vent.on('showErr',this.showErr,this)
		},
		showErr:function(text){
			this.$el.html(text);
			$('.toSide').append(this.el);
		}
	})
	
	
	//vent.trigger('editTaskNumber',id)
	var submodel = new App.Models.Sub();
	new App.Views.submitURL({model:submodel});
	new App.Views.subs({model:submodel});
	var err = new App.Views.err()
	
})();
