## Tender Based Pricing

This code adds a checkbox to the variations in WooCommerce to indicate that it is tender-based in pricing. The checkbox is integrated to exclude tender-based variations from the 'From - To' price range text displayed on the frontend, give the products a price of 0, and to show tender based at the review-order page. The tender-based variations are then replaced with a 'Contact us for pricing' string on the frontend using a filter. 

The standard WooCommerce page/section review-order.php is overwritten by being hardlinked to the themes woocommerce/checkout directory.