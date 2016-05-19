
function logIn(){
	//TO DO: logic
}

function redirect(URL){
	window.location.href = URL;
}

function insertAfter(newNode, reference){
	reference.parentNode.insertBefore(newNode, reference.nextSibling);
} 

function isNumeric(number) {
  return !isNaN(parseFloat(number)) && isFinite(number);
}

