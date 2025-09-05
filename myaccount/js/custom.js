	$('.imagesslider').each(function(index, el) {
		console.log($(el), $(el).data());
		$(el).bxSlider($(el).data('settings'));
	});
	$('.gallery_items').each(function() {
		$(this).magnificPopup({
			delegate: '.gallery_item',
			type: 'image',
			zoom: {
				enabled: true
			},
			gallery: {
				enabled: true
			}
		});
	});
	$('.product-images').each(function() {
		$(this).magnificPopup({
			delegate: '.product-image',
			type: 'image',
			zoom: {
				enabled: true
			},
			gallery: {
				enabled: true
			}
		});
	});
	$('.videos').each(function() {
	$(this).magnificPopup({
		delegate: '.video',
		type: 'iframe',
		preloader: false,
		gallery: {
			enabled: true
		}
	});
});
function FillBilling(f) {
	if (f.billingtoo.checked == true) {
		f.recipient_name.value = f.cust_name.value;
		//f.recipientbusiness.value = f.business.value;
		f.recipient_address1.value = f.cust_address1.value;
		f.recipient_address2.value = f.cust_address2.value;
		f.recipient_town.value = f.cust_town.value;
		f.recipient_county.value = f.cust_county.value;
		f.recipient_postcode.value = f.cust_postcode.value;
		f.recipient_telephone.value = f.cust_telephone.value;
	} else {
		f.recipient_name.value = '';
		//f.requiredrecipientbusiness.value = '';
		f.recipient_address1.value = '';
		f.recipient_address2.value = '';
		f.recipient_town.value = '';
		f.recipient_county.value = '';
		f.recipient_postcode.value = '';
		f.recipient_telephone.value = '';
	}
}
$('.orderformselect').on('change', function(event) {
	updateprodprice();
});
$('.orderformqty').on('keyup keydown', function(event) {
	updateprodprice();
});

function updateprodprice() {
	var price = 0.00;
	var options = $('.orderformselect');
	var qty = $('.orderformqty').val();

	console.log(qty, options);

	price = $('#product_price').data('default-price');

	options.each(function(index, el) {
		console.log(index, $(el).val(), $('option[value="' + $(el).val() + '"]').data('option-price'));
		var option_price = $('option[value="' + $(el).val() + '"]').data('option-price');

		price = +price + +option_price;
	});

	price = price * qty;
	price = price.toFixed(2);

	console.log(price);

	if (price > 0) {
		$('#product_price').html(price);
	}
}