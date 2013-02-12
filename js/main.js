(function(){
	window.sprite = $('#zipit').sprite({cellSize: [131,54],cells: [1, 15],initCell: [0,0],interval: 50});
	
	window.App = {
		Models:{},
		Views:{},
		Router:{}	
	}
		
	window.showErr = function(text){
		var el =  document.getElementById('err');
		if(!el){
			$('.toSide').append('<div id="err">'+text+'</div>');
		}else{
			$('#err').html(text);
			$('#err').fadeIn(100);
		}
		$('#err').delay(5000).fadeOut(100);
	}
	
	App.Models.Sub = Backbone.Model.extend({
		validate:function(attr){
			if(!$.trim(attr.lyndaUrl) ){
				return 'please enter download link.'	
			}
		}
	});
	
	App.Views.subs = Backbone.View.extend({
		el:'#back',
		
		initialize:function(){
			this.model.on('change:lyndaUrl',this.changedurl,this);
		},
		events:{
			'click #retry':'retry'	
		},
		
		retry:function(){
			this.model.set('lyndaUrl','#');
			sprite.stop();
			sprite.col(1);
			$('#downlodit').attr('href','#');
			$('#lyndaURL').find('input[type=text]').val('');
			$('#retry').animate({height:0},50);
			$('#inputs').removeClass('flipped');
		},
		
		changedurl:function(){
			if( this.model.get('lyndaUrl') == '#') return
			sprite.go();
			$.ajax({ 
			  type: 'get', 
			  url: 'index.php',
			  data:{ url: $.trim(this.model.get('lyndaUrl')),api:1},
			  success: function(data) {
				  if(data.success){
				  	$('#downlodit').attr('href',data.success);
				    $('#inputs').addClass('flipped');
					$('#retry').delay(1000).show().animate({height:20},50);
				  }else{
					showErr(data.error);
					sprite.stop();
					sprite.col(1);
				  }
				  	
			  },
			  error: function(xhr, ajaxOptions, thrownError) {
			      console.log(xhr.toSource() +' - '+thrownError);
				  showErr('Check URL please!');
				  sprite.stop();
				  sprite.col(1);
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
				showErr('Oops, check your url please!');	
			}
		}
	});

	window.submodel = new App.Models.Sub();
	new App.Views.submitURL({model:submodel});
	new App.Views.subs({model:submodel});
	
})();