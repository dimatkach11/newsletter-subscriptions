// JavaScript code for public-facing functionality
function resetFilters() {
	// Get the current page URL without query parameters
	var currentUrl = window.location.href.split('?')[0]

	// Maintain other GET parameters by extracting them from the URL
	var otherParameters = []
	var queryParams = window.location.search.substr(1).split('&')
	for (var i = 0; i < queryParams.length; i++) {
		var param = queryParams[i].split('=')
		if (param[0] !== 'start_date' && param[0] !== 'end_date') {
			otherParameters.push(queryParams[i])
		}
	}

	// Combine the current page URL, other parameters, and the page parameter
	var newUrl = currentUrl + '?page=newsletter-subscriptions'
	if (otherParameters.length > 0) {
		newUrl += '&' + otherParameters.join('&')
	}

	// Redirect to the new URL
	window.location.href = newUrl
}
