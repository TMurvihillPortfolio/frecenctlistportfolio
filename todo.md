# TODO

- delete category name after unclick if none left
    1. pass in category to javascript
    2. after display none check to see if any of that category are displayed
        - from div = cat or fre header 
            -> next element sibling (the item form) -> 
            IF has children -> 
                foreach child 
                    IF (!display none) return true, 
                    ELSE -> 
                        get rid of cat or fre header
                        return
            ELSE -> get rid of cat or fre header
    3. if no clicks display category title

### Low Priority
- get background image fed from Cloudinary

### FUTURE FEATURES !!

- up/down arrows to adjust quantities with possible increment field
- add/edit/reorder categories
- add/edit lists
- allow multiple users
- allow user to adjust frecency parameters