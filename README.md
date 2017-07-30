Contao Isotope Simple ERP
=======================

[![](https://img.shields.io/badge/Contao%204-compatible-orange.svg?style=flat-square&logo=data%3Aimage%2Fsvg%2Bxml%3Bbase64%2CPD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iRWJlbmVfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHdpZHRoPSIxNHB4IiBoZWlnaHQ9IjEyLjIzcHgiIHZpZXdCb3g9IjI0LjExNiAyMS4wNDMgMTQgMTIuMjMiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMjQuMTE2IDIxLjA0MyAxNCAxMi4yMyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI%2BPHBhdGggZmlsbD0iI0ZGRkZGRiIgZD0iTTI1LjA0MiwyMS4wNDNjLTAuNTExLDAtMC45MjUsMC40MTQtMC45MjYsMC45MjR2MTAuMzgxYzAuMDAxLDAuNTExLDAuNDE2LDAuOTI1LDAuOTI2LDAuOTI1SDM3LjE5YzAuNTA5LDAsMC45MjMtMC40MTEsMC45MjYtMC45MjFWMjEuOTY4YzAtMC41MTEtMC40MTUtMC45MjUtMC45MjYtMC45MjRIMjUuMDQyeiIvPjxwYXRoIGZpbGw9IiNGNDdDMDAiIGQ9Ik0yNS4zNjEsMjguNDZjMC4zOTIsMS44MjMsMC43MTgsMy41NiwxLjg2LDQuODFoLTIuMTc5Yy0wLjUwOSwwLTAuOTIzLTAuNDExLTAuOTI2LTAuOTIxVjIxLjk2OGMwLjAwMS0wLjUxMSwwLjQxNi0wLjkyNCwwLjkyNi0wLjkyNGgxLjU3NGMtMC4zODQsMC4zNS0wLjcyMSwwLjc0OC0xLjAwMiwxLjE4NEMyNC4zNjksMjQuMTM2LDI0Ljg4MSwyNi4yMTcsMjUuMzYxLDI4LjQ2eiBNMzcuMTksMjEuMDQzaC0yLjQ3YzAuNTg0LDAuNTg1LDEuMDc4LDEuMzQyLDEuNDUsMi4yODRsLTMuNzU1LDAuNzkyYy0wLjQxMi0wLjc4OS0xLjAzNy0xLjQ0NS0yLjI2MS0xLjE4N2MtMC42NzUsMC4xNDMtMS4xMjMsMC41MjEtMS4zMjEsMC45MzZjLTAuMjQ0LDAuNTExLTAuMzY0LDEuMDg0LDAuMjE5LDMuODA4YzAuNTgzLDIuNzI0LDAuOTI3LDMuMTk4LDEuMzU4LDMuNTY2YzAuMzUxLDAuMjk4LDAuOTE2LDAuNDY0LDEuNTkxLDAuMzIxYzEuMjI1LTAuMjU4LDEuNTI2LTEuMTEsMS41OC0xLjk5N2wzLjc1NS0wLjc5M2MwLjA4OCwxLjk0Mi0wLjUxMywzLjQ0OS0xLjU3NCw0LjQ5MWgxLjQyOGMwLjUwOSwwLDAuOTIzLTAuNDExLDAuOTI2LTAuOTIxVjIxLjk2OEMzOC4xMTYsMjEuNDU2LDM3LjcwMSwyMS4wNDMsMzcuMTksMjEuMDQzeiIvPjwvc3ZnPg%3D%3D)](https://contao.org/) [![](https://img.shields.io/packagist/v/numero2/contao-isotope-simple-erp.svg?style=flat-square)](https://packagist.org/packages/numero2/contao-isotope-simple-erp) [![](https://img.shields.io/badge/License-LGPL%20v3-blue.svg?style=flat-square)](http://www.gnu.org/licenses/lgpl-3.0)

About
--
Most basic ERP system for Isotope eCommerce.

When bought, the products "availability" counter gets decreased.
If configured, products will be automatically suppressed once no quantity is available.

If a product has a limited quantity configured the users won't be able to put more in their cart as there is in total.

There is a simple message in backend which lets you know how many products are currently unavailable.

System requirements
--

* [Contao](https://github.com/contao/core) 3.2.5 or newer  / successfully tested with Contao 4.4.1
* [Isotope eCommerce](https://github.com/isotope/core) 2.4.1 or higher

Installation & Configuration
--

* Create a folder named `isotope_simple_erp` in `system/modules`
* Clone this repository into the new folder
* Run a database update via the Installtool
* Make sure to activate `Quantity available` and `Suppress on zero?` in your product type

**Additional step for Contao 4.X:**
Open `app/AppKernel.php` and add the following line to the $bundles array
```php
new Contao\CoreBundle\HttpKernel\Bundle\ContaoModuleBundle('isotope_simple_erp', $this->getRootDir())
```