document.observe("dom:loaded", function() {
    if (typeof(Product) != 'undefined' && typeof(Product.OptionsPrice) != 'undefined') {
        Product.OptionsPrice.prototype.formatPrice = function(price) {
            var precision = wpTrimZeroRight(price, this.priceFormat.precision);
            this.priceFormat.precision          = precision;
            this.priceFormat.requiredPrecision  = precision;
            return formatCurrency(price, this.priceFormat);
        };
        if (typeof(optionsPrice) != 'undefined') {
            optionsPrice.reload(); // --- FIX: for magento 1.4.x.x
        }
    }

});

function wpTrimZeroRight(price, precision) {
    var format = {
        'pattern': '%s',
        'precision': precision,
        'requiredPrecision': precision,
        'decimalSymbol': '.',
        'groupSymbol': '',
        'groupLength': 0,
        'integerRequired': 1
    };
    var xPrice = formatCurrency(price, format);
    var decimal = '';
    var pointPos = xPrice.lastIndexOf('.');
    if (pointPos !== -1) decimal = xPrice.substr(pointPos);
    var c1 = decimal.length;
    decimal = decimal.replace(new RegExp("[0]+$", "g"), "");
    var c2 = decimal.length;
    var xPrecision = precision - (c1 - c2);
    return xPrecision;
}
