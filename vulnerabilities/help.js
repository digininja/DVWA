function show_answer(which) {
	var block = document.getElementById(which + "_answer");
	var button = document.getElementById(which + "_button");
	if (block.style.display === "" || block.style.display === "none") {
		block.style.display = "block";
		button.innerText = "Hide Answer";
	} else {
		block.style.display = "none";
		button.innerText = "Show Answer";
	}
}
