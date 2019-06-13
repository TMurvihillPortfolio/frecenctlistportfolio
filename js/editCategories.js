/***********************
 * Edit Category
 * ********************/

/* helper function, get index of div in list of divs */
function getChildIndex(originChildElement) {
    let i = -1;
    let childElement = originChildElement;
    while(childElement) {
        childElement = childElement.previousElementSibling;
        i++;
    }
    console.log('index',i);
    return i;
}
// /*Populate initial list*/
let catList = document.querySelector("#js--PHPArrayTransfer").innerText;
console.log('catList', catList);

function renderBoxes(data) {
    document.querySelector('#container').innerHTML = '';
    const dataArray = data.split(',');
    dataArray.forEach((el, index) => {
        let element = `<div class='editCategories__listItem dropTarget'>
                    <div class="flex editCategories__listItem--dragGrouping childPointerNone" draggable="true"><p>${el}</p><img src='./img/dragDropBars.png' draggable=false /></div>
                </div>`;


        // let element = `<div class='box box${index} droptarget' id='box${index}'><p draggable="true" id="dragTarget${el}">${el}</p></div>`;
        document.querySelector('#container').innerHTML += element;
    });
}

function getCategoryArray() {
    
}

/*Prepare data array*/
let dragStartingDiv = '';
let dragStartingContent = '';
let dragEnterDiv = '';

//let categories=['data0', 'data1', 'data2', 'data3', 'data4', 'data5', 'data6'];
renderBoxes(catList);


// /* ----------------- Events fired on the drag target ----------------- */
            
document.addEventListener("dragstart", function(event) {
    // The dataTransfer.setData() method sets the data type and the value of the dragged data
    event.dataTransfer.setData("Text", event.target.id);
    dragStartingDiv = event.target.parentElement;
    dragStartingContent = event.target.innerText;
    
    // Change the opacity of the draggable element
    event.target.style.opacity = "0.4";
    
});

// // While dragging the p element, you may enter a feature here
// document.addEventListener("drag", function(event) {
//     //add event to perform while dragging          
// });

// reset the opacity
document.addEventListener("dragend", function(event) {
    event.target.style.opacity = "1";
    //console.log('dragend');
});


// /* ----------------- Events fired on the drop target ----------------- */

// When the draggable p element enters the droptarget, change the DIVS's border style
document.addEventListener("dragenter", function(event) {
    const enteredBox = event.target.classList.contains('dropTarget') ? event.target : event.target.parentElement;   
    dragEnterDiv = event.target;
    if (enteredBox.classList.contains("dropTarget")) {
        //console.log('dragenter if statement');
        enteredBox.style.border = "2px solid #083a08";
        //enteredBox.style.transform = "scale(1.05)";

    }
});

// By default, data/elements cannot be dropped in other elements. To allow a drop, we must prevent the default handling of the element
document.addEventListener("dragover", function(event) {
    event.preventDefault();
    //console.log("dragover");
});

// When the draggable element leaves the droptarget, reset the DIVS's border style
document.addEventListener("dragleave", function(event) {
    const leftBox = event.target.classList.contains('dropTarget') ? event.target : event.target.parentElement;   
    
    if (leftBox.classList.contains("dropTarget") && !(dragEnterDiv.classList.contains('childPointerNone'))) {
        //console.log('dragleave');
        //leftBox.transform = "scale(1)";
        leftBox.style.border="1px solid #3C7496";
    }
});

// /* On drop - Prevent the browser default handling of the data (default is open as link on drop)
//     Get the dragged data with the dataTransfer.getData() method
//     Reorder the array
//     Rerender the list
// */
document.addEventListener("drop", function(event) {
    event.preventDefault();
    const targetBox = event.target.classList.contains('dropTarget') ? event.target : event.target.parentElement;
    
    //console.log('dropItem');
    if (targetBox.classList.contains("dropTarget")) {
        targetBox.style.border="1px solid #3C7496";
        console.log("event target", event.target);

        console.log('targetBox', targetBox);
        console.log('targetBox', targetBox.children[0].innerText);
        console.log('dragStartingDiv', dragStartingDiv.children[0].innerText);

        let data = catList.split(',');
        console.log('dataBefore', data);
        
        //get index of origin and target elements
        const dragOriginIndex = getChildIndex(dragStartingDiv);                 
        const dragTargetIndex = getChildIndex(targetBox);

        //delete dragged item from original location in array
        data.splice(dragOriginIndex,1);
        
        //add dragged item to new location in array
        data.splice(dragTargetIndex, 0, dragStartingContent);
              
        //update PHPTransfer field
        catList = data.join();
        document.querySelector("#js--PHPArrayTransfer").innerText = catList;
        console.log('PHPTransferFieldUpdate', catList);
        
        //rerender list       
        renderBoxes(catList);
        
        //update database
        updateCategoryOrder(catList, 'testit2');
    }

    //Fire an api to change the category db order
});