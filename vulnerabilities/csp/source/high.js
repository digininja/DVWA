function clickButton() {
    var s = document.createElement("script");
    s.src = "source/jsonp.php?callback=solveSum";
    document.body.appendChild(s);
}

function solveSum(obj) {
	if ("answer" in obj) {
		document.getElementById("answer").innerHTML = obj['answer'];
	}
}

var solve_button = document.getElementById ("solve");

if (solve_button) {
	solve_button.addEventListener("click", function() {
		clickButton();
	});
}
