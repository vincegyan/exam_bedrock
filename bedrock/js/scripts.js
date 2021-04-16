const links = document.querySelectorAll(".main-navigation ul a");

	for (const link of links) {
		link.addEventListener("click", clickHandler);
	}

	function clickHandler(e) {
		e.preventDefault();
		const href = this.getAttribute("href");

		document.querySelector(href).scrollIntoView({
			behavior: "smooth"
		});
	}