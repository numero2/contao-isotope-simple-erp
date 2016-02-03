# contao-isotope-simple-erp #


## About ##

Most basic ERP system for Isotope.
When bought, the products "availability" counter gets decreased.
If configured, products will be automatically suppressed once no quantity is available.

There is a simple message in backend which lets you know how many products are currently unavailable.


## Installation ##

* Copy `system` folder into Contao installation
* Make sure to activate `Quantity available` and `Suppress on zero?` in your product type


## Dependencies ##

* [Contao](https://github.com/contao/core) 3.5 or higher
* [Isotope eCommerce](https://github.com/isotope/core) 2.3 rc2 or higher