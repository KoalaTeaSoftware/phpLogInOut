# PHP Membership Management
This is very a very simple 'plugin'. The actual log-n / sign-up / log-out guts have fingers in:
* database management
* session management
* customised error logging
## Installation
* Simply grab the files and put them into a good place
    * You may need to be careful with merging files
     * **Take care not to have spurious chars between the code that starts the session and the start of the HTML that is the page**
* You may want to add client-side constraints to the form fields. I have omitted them here so that the client-side validation is easier to achieve by manipulating the UI.
    * make the fields required (a value near the top of the form drawing file)
    * add size constraints
    * add pattern constraints
## Operation
* loginDrawForm.php will actually draw the form, so require it where you want to see the form
* loginAction.php is the destination of the form submit buttons
    * You may want to adjust the destination indicated (by a constant at the top) in the loginAction.inc.php
    * You may want to alter the regexp that is used to decide if passwords are acceptable.
* It adds a little data to the session to indicate the logged-in-ness
    * A more secure / robust form of session management without adjusting this code (although you may chose to use handlerSession.php to house this stuff).
* for all of the components, a GET parameter of status is used (for the purpose you expect), even the database shim.
