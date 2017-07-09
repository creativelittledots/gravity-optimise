# Optimal Gravity

This repo contains some helpful code to cleanup and make better use of Gravity Forms:

* Filtering markup before it returns to the client
  * Running find and replace on the markup
* Fixing JS issues where Gravity Forms runs on the page before jQuery has declared in the footer
* Cleaning Gravity Forms Email Notifications 
  * Setting emails to text format
  * Disabling auto formating line breaks
  * Allowing emails to run through another plugin such WP Better Emails
  * Allowing emails to run through WooCommerce Email Template
* Nesting Block Fields
  * Ability to create Nested Blocks with a Block Title
  * Fields within the Block are nested such that: 
  
  ```html
  <li class="block"><h3>Title</h3><ul class="fields"><li class="field"></li></ul>
  ```

