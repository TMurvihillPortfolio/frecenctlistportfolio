window.onload = () => {
    
}
function prepareEnvironmentAddItemForm() {
    document.getElementById('js--addItemForm').style.display = 'block';
    document.getElementById('js--addItemOrderBy').style.display = 'none';
    document.getElementById('js--addItemFilterBy').style.display = 'none';
    document.getElementById('js--addItemListContainer').style.display = 'none';
}
function restoreEnvironmentAddItemForm() {
    document.getElementById('js--addItemForm').style.display = 'none';
    document.getElementById('js--addItemOrderBy').style.display = 'block';
    document.getElementById('js--addItemFilterBy').style.display = 'block';
    document.getElementById('js--addItemListContainer').style.display = 'block';
}

