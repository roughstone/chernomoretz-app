export default class DragElement {

    constructor() {

    }

dragElement(elmnt, cursor) {
    let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0
    if(!cursor) {
        document.getElementById(elmnt.id).onmousedown = dragMouseDown
    } else {
        document.getElementById(cursor.id).onmousedown = dragMouseDown
    }

    function dragMouseDown(e) {
        e = e || window.event
        e.preventDefault()
        // get the mouse cursor position at startu
        pos3 = e.clientX
        pos4 = e.clientY
        document.onmouseup = closeDragElement
        // call a function whenever the cursor moves
        document.onmousemove = elementDrag
    }
    function elementDrag(e) {
        e = e || window.event
        e.preventDefault()
        // calculate the new cursor position
        pos1 = pos3 - e.clientX
        pos2 = pos4 - e.clientY
        pos3 = e.clientX
        pos4 = e.clientY
        // set the element's new position:
        elmnt.style.top = (elmnt.offsetTop - pos2) + "px"
        elmnt.style.left = (elmnt.offsetLeft - pos1) + "px"

        if(parseFloat(elmnt.offsetTop) + parseFloat(elmnt.style.height) > parseFloat(elmnt.parentElement.style.height) - 1) {
            elmnt.style.top = parseFloat(elmnt.parentElement.style.height) - parseFloat(elmnt.style.height) + "px"
        }
        if(parseFloat(elmnt.offsetLeft) + parseFloat(elmnt.style.width) > parseFloat(elmnt.parentElement.style.width) - 1) {
            elmnt.style.left = parseFloat(elmnt.parentElement.style.width) - parseFloat(elmnt.style.width) + "px"
        }
        if(parseFloat(elmnt.offsetLeft) < 1) {
            elmnt.style.left = 0 + "px"
        }
        if(parseFloat(elmnt.offsetTop) < 1) {
            elmnt.style.top = 0 + "px"
        }
    }

    function closeDragElement() { // stop moving when mouse button is released
        document.onmouseup = null
        document.onmousemove = null
    }
}
}
