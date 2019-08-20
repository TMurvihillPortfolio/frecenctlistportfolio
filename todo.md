# TODO

- list sort function in helpers line 178 busted, commented out for now
- frecency calc needs to be run on num clicks on render list to accommodate new date-
- add/edit categories
- convert categories to custom categories when customer goes <i>Premium</i>
- categories page needs to be responsive
- try catch to add/edit lists
- add lists needs cancel button

## Prior to launch

- expand catgories (https://edsoehnel.com/retail-cpg-grocery-categories/)
    - <s>create database test environment</s>
    - <s>add new categories</s>
    - change current categories to category ids in test list item table
    - test everything in test environment
    - go live

- test payPal live signup
- check for exit(); left from debugging
 
## Medium Priority

- follow up with an unactivated account
- page that fully explains pay levels
- way to count items and notify customer when they are going to need to move to new level
- PWA functionality
- on lists page only allow one add/edit at a time, dialogue if user clicks elsewhere
- ListItem categories in db should be by category id, not word

### Low Priority

- check for duplicate list name
- on tap out of change list select, revert to nice italic
- create a way to contact support
- get background image fed from Cloudinary
- sanitize cancelPremium input for if == statement to lowercase etc.
- add scroll to functionality to addEditLists.php for after delete or edit
- refactor sql insert item on signup into an array

### FUTURE FEATURES !!

- up/down arrows to adjust quantities with possible increment field
- add/edit categories
- allow multiple users
- allow user to adjust frecency parameters