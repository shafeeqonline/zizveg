var alldata = [];
$.ajax({
	url: "/js/products.json",
	dataType: "json"
})
.done(function(data){
	alldata = data.productslist;
	mainLoader();
})
function mainLoader(){
	var templatingFunction = function(localdata){
		$.each(localdata, function(i, v){
			var $mytemplate = $('<tr/>'), inputhtml = '<input type="text" placeholder="0">';
			$('<td/>').addClass('product-name').text(v.name).appendTo($mytemplate);
			$('<td/>', {'data-cost' : v.cost}).addClass('product-cost').text(v.costText).appendTo($mytemplate);
			$('<td/>').addClass('product-quantity').html(inputhtml).appendTo($mytemplate);
			$('<td/>', {'data-amount' : '0'}).addClass('product-amount').text(0).appendTo($mytemplate);
			$mytemplate.appendTo($('#shafeeq'))
		})
	}

	if($('body').hasClass('home')){
		templatingFunction(alldata);
	}

	var calcTotal = function(){
		var totalCostNow = 0;
		$('table tbody .product-amount').each(function(){
			totalCostNow += parseInt($(this).data('amount'));
		})
		if(totalCostNow > 0){
			$('.sticky-footer').show();
			$('.sticky-footer span').eq(1).text(totalCostNow);
		}
		else
		$('.sticky-footer').hide();
	}

	$('#shafeeq').on('input', '.product-quantity input', function(){
		$this = $(this);
		jElParent = $this.closest('tr');
		$('#shafeeq tr').show();
		$('#searchproducts').val('');
		var amount = $this.val() * jElParent.find('.product-cost').data('cost');
		jElParent.find('.product-amount').text(amount);
		jElParent.find('.product-amount').data('amount', amount);
		calcTotal();
	})

	$('body').on('click', "#localsearchproducts" , function(){
		$('#searchproducts').focus();
	})

	$('body').on('click', "#loggout" , function(){
		alert("jgsdf")
	})

	if($.cookie('authenticated') == "true" && ($.cookie('username'))){
		$('#loggout').show();
	}


	if($('body').hasClass('order')){
		if($.cookie('authenticated') != "true"){
			window.location= "/index.html";
		}
		var localObject = JSON.parse(localStorage.getItem('productlist'));
		if(jQuery.isEmptyObject(localObject))
		window.location = "/index.html";
		templatingFunction(localObject);
		var totalValue = 0;
		$('#shafeeq tr .product-quantity input').each(function(i){
			totalValue += localObject[i]['quantity'] * localObject[i]['cost'];
			$(this).val(localObject[i]['quantity']).trigger('input');
		})
		$('#totalbill').val("Total bill: "+ totalValue);
	}

	$('#searchproducts').on("keyup", function() {
		var g = $(this).val().toLowerCase();
		$("#shafeeq .product-name").each(function() {
			var s = $(this).text().toLowerCase();
			$(this).closest('tr')[ s.indexOf(g) !== -1 ? 'show' : 'hide' ]();
		});
	});

	$('#place-order').on('click', function(){
		var productlist = [];
		$.each($('#shafeeq tr .product-quantity input'), function(){
			if($(this).val() > 0){
				var productObject = {};
				productObject.name = $(this).closest('tr').find('.product-name').text();
				productObject.cost = $(this).closest('tr').find('.product-cost').data('cost');
				productObject.costText = $(this).closest('tr').find('.product-cost').text();
				productObject.quantity = $(this).closest('tr').find('.product-quantity input').val();
				productlist.push(productObject);
			}
		})
		localStorage.removeItem('productlist');
		localStorage.setItem('productlist', JSON.stringify(productlist));
		if($.cookie('authenticated') == "true" && ($.cookie('username'))){
			window.location = "/placeorder.html";
		}
		else{
			window.location = "/login.html";
		}
	})
	//Submit register form
	$('form#register').on('submit', function(e){
		e.preventDefault();
		var dataToSend = {};
		$(this).find('input').each(function(){
			dataToSend[$(this).attr('name')] = $(this).val();
		})
		$.ajax({
			method: "POST",
			url: "queryexec.php",
			data: dataToSend
		})
		.done(function(data) {
			if(data == "done"){
				$.cookie('authenticated', 'true', { expires: 7, path: '/' });
				$.cookie('username', $('input[name="username"]').val(), { expires: 7, path: '/' });
				window.location = "/placeorder.html";
			}
			else if(data == "multipleentries"){
				alert("Username already exists")
			}
			else{
				alert("Sorry Please try again")
			}
		})
		.fail(function() {
			alert( "error" );
		})
	})

	//Submit final order
	$('#finalplace').on('click', function(e){
		e.preventDefault();
		var localObject = JSON.parse(localStorage.getItem('productlist')) , dataToSend = {};
		if(jQuery.isEmptyObject(localObject) || !$.cookie('authenticated') || !$.cookie('username'))
		window.location = "/index.html";
		dataToSend.order = localObject;
		dataToSend.username = $.cookie('username');
		$.ajax({
			method: "POST",
			url: "placeorder.php",
			data: {"myData":dataToSend}
		})
		.done(function(data) {
			if(data == "success"){
				alert("Order sucessful");
				localStorage.removeItem('productlist');
				window.location = "/index.html";
			}
			else{
				alert("Sorry order not placed");
				window.location = "/index.html";
			}
		})
		.fail(function() {
			alert( "error" );
		})
	})
	$('#hideonscroll').hide();
	//Submit login form
	$('form#login').on('submit', function(e){
		e.preventDefault();
		var dataToSend = {};
		$(this).find('input').each(function(){
			dataToSend[$(this).attr('name')] = $(this).val();
		});
		$.ajax({
			method: "POST",
			url: "credentialscheck.php",
			data: dataToSend
		})
		.done(function(data) {
			if(data == "1"){
				$.cookie('username', $('input[name="username"]').val(), { expires: 7, path: '/' });
				$.cookie('authenticated', 'true', { expires: 7, path: '/' });
				window.location = "/placeorder.html";
			}
			else{
				alert("wrong credentials");
			}
		})
		.fail(function() {
			alert( "error" );
		})
	})
};
