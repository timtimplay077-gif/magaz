function button1() {
    fetch("script.php", {
        method: "GET",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "name=фывафыва"
    })
        .then(response => response.text())
        .then(date => {
            console.log(date);
        })
        .catch(error => console.error(error))
}