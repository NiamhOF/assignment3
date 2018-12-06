function retrieveCustomerInformation(rowId, customerNumber) {
    const limit = document.getElementById("limit")["value"]

    var url = './payments.php';
    var formData = new FormData();
    formData.append('customerNumber', customerNumber);
    formData.append('limit', limit);
    formData.append('rowId', rowId);

    fetch(url, { method: 'POST', body: formData })
        .then(function (response) {return response.text()})
        .then(function (body) { document.body.innerHTML = body });
}

function retrieveLimits() {
    const limit = document.getElementById("limit")["value"]

    var url = './payments.php';
    var formData = new FormData();
    formData.append('limit', limit);

    fetch(url, { method: 'POST', body: formData })
        .then(function (response) {return response.text()})
        .then(function (body) { document.body.innerHTML = body });
}