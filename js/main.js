(function(){
	window.sprite = $('#zipit').sprite({cellSize: [131,54],cells: [1, 15],initCell: [0,0],interval: 50});
	
	window.App = {
		Models:{},
		Views:{},
		Router:{}	
	}
	
	
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
			  url: 'app.php',
			  data:{ url: this.model.get('lyndaUrl')},
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
			this.model.set('lyndaUrl',newURL);
		}
	});
	
	
	
	var submodel = new App.Models.Sub();
	new App.Views.submitURL({model:submodel});
	new App.Views.subs({model:submodel});
	
})();
