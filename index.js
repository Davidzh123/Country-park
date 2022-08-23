let request = new XMLHttpRequest();

const fetchLocationButton = document.getElementById("fetch-locations");
const cancelButton = document.getElementById("cancel");
const loader = document.getElementById("loader");
const error = document.getElementById("error");

loader.style.visibility = "hidden";

cancelButton.disabled = true ;

function fetchLocations(){
    const tableBody = document.getElementById("table-body");

    fetchLocationButton.disbled = true;
    loader.style.visibility="visible";
    cancelButton.disabled ="false";
    error.innerHTML = "";
    tableBody.innerHTML = "";


    function onResponse(){
        xmldoc = request.responseXML
        const locations = xmldoc.children[0];
        const allLocations = locations.children;
        console.log(locations);

        for (const location of allLocations){
            const title = location.children[0];
            const description = location.children[1]
            const prix = location.children[3];
            console.log(title);
            console.log(description);
            console.log(prix);

            const titleContent = title.textContent.trim();
            const descriptionContent = description.textContent.trim();
            const prixContent = prix.textContent.trim();
            const imageUrl = location.children[2].getAttribute("url");

            const tableRow = document.createElement("tr");
            const titleTableCell = document.createElement("td");
            const descriptionTablecell = document.createElement("td");
            const prixTableCell = document.createElement("td");
            const imageTableCell = document.createElement("td")
            const imagePreview = document.createElement("img");

            titleTableCell.innerText = titleContent;

            descriptionTablecell.innerText = descriptionContent;

            prixTableCell.innerText = prixContent;

            imagePreview.src = imageUrl;
            imagePreview.style.height = "50px";
            imagePreview.style.width = "100px";
            imagePreview.style.cursor = "button";
            imagePreview.addEventListener("click",function (){
                window.open(imageUrl);
            });

            imageTableCell.appendChild(imagePreview);

            tableRow.appendChild(titleTableCell);
            tableRow.appendChild(descriptionTablecell);
            tableRow.appendChild(prixTableCell);
            tableRow.appendChild(imageTableCell);

            tableBody.appendChild(tableRow);

            fetchLocationButton.disbled = false;
            loader.style.visibility = "hidden";
            cancelButton.disabled= true;
        };

        function onCancelrequest(){
            request.abort();
            fetchLocationButton.disbled = false;
            cancelButton.disabled = true;
            loader.style.visibility = "hidden";
        }

        cancelButton.addEventListener("click",onCancelrequest);

        request.addEventListener("abort",function (){
            error.innerText = "La requete a été annulée";
        });
    }

    request.addEventListener("load", onResponse);
    request.open("GET","./index.xml");
    request.send();

}


fetchLocationButton.addEventListener("click", fetchLocations);



