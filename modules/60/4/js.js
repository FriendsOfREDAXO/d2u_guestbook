(function() {
	function getModule(moduleId) {
		return document.getElementById(moduleId);
	}

	window.d2uGuestbookSetStars_60_4 = function(moduleId, value) {
		for (var index = 1; index <= 5; index++) {
			var star = document.getElementById(moduleId + "_star" + index);
			if (!star) {
				continue;
			}
			star.classList.toggle("fas", index <= value);
			star.classList.toggle("far", index > value);
		}
	};

	window.d2uGuestbookResetStars_60_4 = function(moduleId) {
		var module = getModule(moduleId);
		if (!module) {
			return;
		}
		var ratingInput = module.querySelector('input[name="rating"]');
		window.d2uGuestbookSetStars_60_4(moduleId, ratingInput ? parseInt(ratingInput.value || "0", 10) : 0);
	};

	window.d2uGuestbookClickStars_60_4 = function(moduleId, value) {
		var module = getModule(moduleId);
		if (!module) {
			return;
		}
		var ratingInput = module.querySelector('input[name="rating"]');
		if (ratingInput) {
			ratingInput.value = value;
		}
		window.d2uGuestbookSetStars_60_4(moduleId, value);
	};

	function showPage(module, pageNumber) {
		var pages = module.querySelectorAll(".guestbook-page");
		var pageLinks = module.querySelectorAll("[data-page-target]");
		pages.forEach(function(page) {
			page.classList.toggle("d-none", page.getAttribute("data-page") !== String(pageNumber));
		});
		pageLinks.forEach(function(link) {
			link.classList.toggle("active-page", link.getAttribute("data-page-target") === String(pageNumber));
		});
	}

	function initPagination(module) {
		var pages = module.querySelectorAll(".guestbook-page");
		if (pages.length <= 1) {
			return;
		}
		showPage(module, 1);
		module.addEventListener("click", function(event) {
			var pageLink = event.target.closest("[data-page-target]");
			if (!pageLink || !module.contains(pageLink)) {
				return;
			}
			event.preventDefault();
			showPage(module, pageLink.getAttribute("data-page-target"));
		});
	}

	function initHashTab(module) {
		if (typeof bootstrap === "undefined" || !bootstrap.Tab || !window.location.hash) {
			return;
		}
		var triggerEl = module.querySelector('a.nav-link[href="' + window.location.hash + '"]');
		if (triggerEl) {
			bootstrap.Tab.getOrCreateInstance(triggerEl).show();
		}
	}

	function initModule(module) {
		if (!module || module.dataset.d2uGuestbook60_4Initialized === "true") {
			return;
		}
		module.dataset.d2uGuestbook60_4Initialized = "true";
		initPagination(module);
		initHashTab(module);
		window.d2uGuestbookResetStars_60_4(module.id);
	}

	function initModules() {
		document.querySelectorAll(".d2u-guestbook-module-60-4").forEach(initModule);
	}

	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", initModules);
	} else {
		initModules();
	}
})();